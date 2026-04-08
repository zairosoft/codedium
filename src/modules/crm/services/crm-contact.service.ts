import { Injectable } from '@nestjs/common';
import { createHash } from 'node:crypto';
import { EventBusService } from '../../../core/events/event-bus.service';
import { HookService } from '../../../core/events/hook.service';
import { CacheService } from '../../../infrastructure/cache/cache.service';
import { CreateContactDto } from '../dto/create-contact.dto';
import { ListContactsDto } from '../dto/list-contacts.dto';
import { UpdateContactDto } from '../dto/update-contact.dto';
import { CrmContactPolicy } from '../policies/crm-contact.policy';
import {
  CrmContactRepository,
  CrmDashboardSummary,
} from '../repositories/crm-contact.repository';
import { CrmContactViewMapper } from '../views/crm-contact.view';

const LIST_TTL_SECONDS = 120;
const DASHBOARD_TTL_SECONDS = 60;
const CONTACT_TTL_SECONDS = 300;

@Injectable()
export class CrmContactService {
  constructor(
    private readonly crmContactRepository: CrmContactRepository,
    private readonly crmContactPolicy: CrmContactPolicy,
    private readonly hookService: HookService,
    private readonly eventBus: EventBusService,
    private readonly cacheService: CacheService,
  ) {}

  async createContact(dto: CreateContactDto) {
    const payload = await this.hookService.emit('customer.beforeCreate', dto);
    const contact = await this.crmContactRepository.create(payload);

    await this.invalidateCollectionCache(contact.id);
    await this.eventBus.emit('customer.afterCreate', {
      contactId: contact.id,
      tenantId: contact.tenantId,
      orgId: contact.orgId,
      email: contact.email,
    });

    return CrmContactViewMapper.toView(contact);
  }

  async getContacts(query: ListContactsDto) {
    const normalizedQuery = this.crmContactPolicy.normalizeListQuery(query);
    const tenantId = this.crmContactRepository.getTenantId();
    const version = await this.getCollectionVersion(tenantId);
    const cacheKey = this.buildListCacheKey(tenantId, version, normalizedQuery);
    return this.cacheService.remember(
      cacheKey,
      LIST_TTL_SECONDS,
      async (): Promise<{
        data: ReturnType<typeof CrmContactViewMapper.toList>;
        total: number;
        page: number;
        limit: number;
      }> => {
        const [contacts, total] = await this.crmContactRepository.findAll(normalizedQuery);
        return {
          data: CrmContactViewMapper.toList(contacts),
          total,
          page: normalizedQuery.page ?? 1,
          limit: normalizedQuery.limit ?? 20,
        };
      },
    );
  }

  async getContactById(id: string) {
    const tenantId = this.crmContactRepository.getTenantId();
    const cacheKey = this.buildDetailCacheKey(tenantId, id);
    return this.cacheService.remember(
      cacheKey,
      CONTACT_TTL_SECONDS,
      async (): Promise<ReturnType<typeof CrmContactViewMapper.toView>> => {
        const contact = await this.crmContactRepository.findById(id);
        return CrmContactViewMapper.toView(contact);
      },
    );
  }

  async updateContact(id: string, dto: UpdateContactDto) {
    const payload = await this.hookService.emit('customer.beforeUpdate', dto);
    const updated = await this.crmContactRepository.update(id, payload);
    await this.invalidateCollectionCache(updated.id);
    await this.eventBus.emit('customer.afterUpdate', {
      contactId: updated.id,
      tenantId: updated.tenantId,
      orgId: updated.orgId,
      email: updated.email,
    });
    return CrmContactViewMapper.toView(updated);
  }

  async removeContact(id: string) {
    const tenantId = this.crmContactRepository.getTenantId();
    await this.crmContactRepository.remove(id);
    await this.cacheService.del(this.buildDetailCacheKey(tenantId, id));
    await this.cacheService.del(this.buildCollectionVersionKey(tenantId));
    await this.eventBus.emit('customer.afterDelete', {
      contactId: id,
      tenantId,
    });
  }

  async getDashboardSummary() {
    const tenantId = this.crmContactRepository.getTenantId();
    const version = await this.getCollectionVersion(tenantId);
    const cacheKey = this.buildDashboardCacheKey(tenantId, version);
    return this.cacheService.remember(
      cacheKey,
      DASHBOARD_TTL_SECONDS,
      async (): Promise<ReturnType<typeof CrmContactViewMapper.toDashboard>> => {
        const summary = await this.crmContactRepository.getDashboardSummary();
        return CrmContactViewMapper.toDashboard(summary);
      },
    );
  }

  private async invalidateCollectionCache(contactId?: string): Promise<void> {
    const tenantId = this.crmContactRepository.getTenantId();
    await this.cacheService.del(this.buildCollectionVersionKey(tenantId));

    if (contactId) {
      await this.cacheService.del(this.buildDetailCacheKey(tenantId, contactId));
    }
  }

  private async getCollectionVersion(tenantId: string): Promise<string> {
    const versionKey = this.buildCollectionVersionKey(tenantId);
    const cachedVersion = await this.cacheService.get<string>(versionKey);
    if (cachedVersion) {
      return cachedVersion;
    }

    const version = Date.now().toString();
    await this.cacheService.set(versionKey, version, CONTACT_TTL_SECONDS);
    return version;
  }

  private buildCollectionVersionKey(tenantId: string): string {
    return `crm:${tenantId}:contacts:version`;
  }

  private buildListCacheKey(tenantId: string, version: string, query: ListContactsDto): string {
    const digest = createHash('sha1').update(JSON.stringify(query)).digest('hex');
    return `crm:${tenantId}:contacts:list:${version}:${digest}`;
  }

  private buildDetailCacheKey(tenantId: string, id: string): string {
    return `crm:${tenantId}:contacts:${id}`;
  }

  private buildDashboardCacheKey(tenantId: string, version: string): string {
    return `crm:${tenantId}:dashboard:${version}`;
  }
}
