import { Inject, Injectable } from '@nestjs/common';
import { createHash } from 'node:crypto';
import { CACHE_PORT, CachePort } from '../../../core/interfaces/cache.interface';
import { EVENT_BUS_PORT, EventBusPort } from '../../../core/interfaces/event-bus.interface';
import { HOOK_PORT, HookPort } from '../../../core/interfaces/hook.interface';
import { CreateContactDto } from '../dto/create-contact.dto';
import { ListContactsDto } from '../dto/list-contacts.dto';
import { UpdateContactDto } from '../dto/update-contact.dto';
import { CrmContactPolicy } from '../policies/crm-contact.policy';
import { CrmContactRepository } from '../repositories/crm-contact.repository';
import { CrmContactViewMapper } from '../views/crm-contact.view';

const LIST_TTL_SECONDS = 120;
const DASHBOARD_TTL_SECONDS = 60;
const CONTACT_TTL_SECONDS = 300;
const MODULE_CACHE_VERSION_KEY = 'crm:module:version';

@Injectable()
export class CrmContactService {
  constructor(
    private readonly crmContactRepository: CrmContactRepository,
    private readonly crmContactPolicy: CrmContactPolicy,
    @Inject(HOOK_PORT)
    private readonly hookService: HookPort,
    @Inject(EVENT_BUS_PORT)
    private readonly eventBus: EventBusPort,
    @Inject(CACHE_PORT)
    private readonly cacheService: CachePort,
  ) {}

  async createContact(dto: CreateContactDto) {
    const payload = await this.hookService.emit('crm.contact.creating', dto);
    const contact = await this.crmContactRepository.create(payload);

    await this.invalidateCollectionCache(contact.id);
    await this.eventBus.emit('crm.contact.created', {
      contactId: contact.id,
      tenantId: contact.tenantId,
      orgId: contact.orgId,
      email: contact.email,
    });
    await this.eventBus.emit('notification.send', {
      name: 'crm.contact.created',
      payload: {
        contactId: contact.id,
        tenantId: contact.tenantId,
        orgId: contact.orgId,
        email: contact.email,
      },
      receivedAt: new Date().toISOString(),
    });

    return CrmContactViewMapper.toView(contact);
  }

  async getContacts(query: ListContactsDto) {
    const normalizedQuery = this.crmContactPolicy.normalizeListQuery(query);
    const tenantId = this.crmContactRepository.getTenantId();
    const [moduleVersion, collectionVersion] = await Promise.all([
      this.getModuleCacheVersion(),
      this.getCollectionVersion(tenantId),
    ]);
    const cacheKey = this.buildListCacheKey(
      tenantId,
      moduleVersion,
      collectionVersion,
      normalizedQuery,
    );
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
    const moduleVersion = await this.getModuleCacheVersion();
    const cacheKey = this.buildDetailCacheKey(tenantId, moduleVersion, id);
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
    const payload = await this.hookService.emit('crm.contact.updating', dto);
    const updated = await this.crmContactRepository.update(id, payload);
    await this.invalidateCollectionCache(updated.id);
    await this.eventBus.emit('crm.contact.updated', {
      contactId: updated.id,
      tenantId: updated.tenantId,
      orgId: updated.orgId,
      email: updated.email,
    });
    await this.eventBus.emit('notification.send', {
      name: 'crm.contact.updated',
      payload: {
        contactId: updated.id,
        tenantId: updated.tenantId,
        orgId: updated.orgId,
        email: updated.email,
      },
      receivedAt: new Date().toISOString(),
    });
    return CrmContactViewMapper.toView(updated);
  }

  async removeContact(id: string) {
    const tenantId = this.crmContactRepository.getTenantId();
    const moduleVersion = await this.getModuleCacheVersion();
    await this.crmContactRepository.remove(id);
    await this.cacheService.del(this.buildDetailCacheKey(tenantId, moduleVersion, id));
    await this.cacheService.del(this.buildCollectionVersionKey(tenantId));
    await this.eventBus.emit('crm.contact.deleted', {
      contactId: id,
      tenantId,
    });
    await this.eventBus.emit('notification.send', {
      name: 'crm.contact.deleted',
      payload: {
        contactId: id,
        tenantId,
      },
      receivedAt: new Date().toISOString(),
    });
  }

  async getDashboardSummary() {
    const tenantId = this.crmContactRepository.getTenantId();
    const [moduleVersion, collectionVersion] = await Promise.all([
      this.getModuleCacheVersion(),
      this.getCollectionVersion(tenantId),
    ]);
    const cacheKey = this.buildDashboardCacheKey(tenantId, moduleVersion, collectionVersion);
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
    const moduleVersion = await this.getModuleCacheVersion();
    await this.cacheService.del(this.buildCollectionVersionKey(tenantId));

    if (contactId) {
      await this.cacheService.del(this.buildDetailCacheKey(tenantId, moduleVersion, contactId));
    }
  }

  private async getModuleCacheVersion(): Promise<string> {
    const cachedVersion = await this.cacheService.get<string>(MODULE_CACHE_VERSION_KEY);
    if (cachedVersion) {
      return cachedVersion;
    }

    const version = '1';
    await this.cacheService.set(MODULE_CACHE_VERSION_KEY, version);
    return version;
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

  private buildListCacheKey(
    tenantId: string,
    moduleVersion: string,
    collectionVersion: string,
    query: ListContactsDto,
  ): string {
    const digest = createHash('sha1').update(JSON.stringify(query)).digest('hex');
    return `crm:${tenantId}:contacts:list:${moduleVersion}:${collectionVersion}:${digest}`;
  }

  private buildDetailCacheKey(tenantId: string, moduleVersion: string, id: string): string {
    return `crm:${tenantId}:contacts:${moduleVersion}:${id}`;
  }

  private buildDashboardCacheKey(
    tenantId: string,
    moduleVersion: string,
    collectionVersion: string,
  ): string {
    return `crm:${tenantId}:dashboard:${moduleVersion}:${collectionVersion}`;
  }
}
