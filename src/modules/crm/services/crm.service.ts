import { Inject, Injectable } from '@nestjs/common';
import { EventEmitter2 } from '@nestjs/event-emitter';
import { CACHE_STORE } from '../../../infrastructure/redis/redis.constants';
import { CacheStore } from '../../../infrastructure/redis/cache.store';
import { CreateContactDto } from '../dto/create-contact.dto';
import { ListContactsDto } from '../dto/list-contacts.dto';
import { UpdateContactDto } from '../dto/update-contact.dto';
import { CrmContactRepository } from '../repositories/crm-contact.repository';
import { CrmContactViewMapper } from '../views/crm-contact.view';

@Injectable()
export class CrmService {
  constructor(
    private readonly crmContactRepository: CrmContactRepository,
    private readonly eventEmitter: EventEmitter2,
    @Inject(CACHE_STORE) private readonly cacheStore: CacheStore,
  ) {}

  async createContact(dto: CreateContactDto) {
    const contact = await this.crmContactRepository.create(dto);
    await this.cacheStore.del('crm:contacts:list');

    this.eventEmitter.emit('crm.contact.created', {
      contactId: contact.id,
      orgId: contact.orgId,
      email: contact.email,
    });

    return CrmContactViewMapper.toView(contact);
  }

  async getContacts(query: ListContactsDto) {
    const cacheKey = `crm:contacts:list:${JSON.stringify(query)}`;
    const cached = await this.cacheStore.get<{
      data: ReturnType<typeof CrmContactViewMapper.toList>;
      total: number;
      page: number;
      limit: number;
    }>(cacheKey);

    if (cached) {
      return cached;
    }

    const [contacts, total] = await this.crmContactRepository.findAll(query);
    const response = {
      data: CrmContactViewMapper.toList(contacts),
      total,
      page: query.page ?? 1,
      limit: query.limit ?? 20,
    };

    await this.cacheStore.set(cacheKey, response, 60);
    return response;
  }

  async getContactById(id: string) {
    const contact = await this.crmContactRepository.findById(id);
    return CrmContactViewMapper.toView(contact);
  }

  async updateContact(id: string, dto: UpdateContactDto) {
    const updated = await this.crmContactRepository.update(id, dto);
    await this.cacheStore.del('crm:contacts:list');
    return CrmContactViewMapper.toView(updated);
  }

  async removeContact(id: string) {
    await this.crmContactRepository.remove(id);
    await this.cacheStore.del('crm:contacts:list');
  }
}

