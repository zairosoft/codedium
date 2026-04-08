import { Injectable } from '@nestjs/common';
import { CreateContactDto } from '../dto/create-contact.dto';
import { ListContactsDto } from '../dto/list-contacts.dto';
import { UpdateContactDto } from '../dto/update-contact.dto';
import { CrmContactService } from './crm-contact.service';

@Injectable()
export class CrmService {
  constructor(private readonly crmContactService: CrmContactService) {}

  async createContact(dto: CreateContactDto) {
    return this.crmContactService.createContact(dto);
  }

  async getContacts(query: ListContactsDto) {
    return this.crmContactService.getContacts(query);
  }

  async getContactById(id: string) {
    return this.crmContactService.getContactById(id);
  }

  async updateContact(id: string, dto: UpdateContactDto) {
    return this.crmContactService.updateContact(id, dto);
  }

  async removeContact(id: string) {
    return this.crmContactService.removeContact(id);
  }
}
