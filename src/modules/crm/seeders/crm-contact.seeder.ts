import { Injectable } from '@nestjs/common';
import { ModuleSeeder } from '../../../workless/lifecycle/module-seeder.interface';
import { DEFAULT_TENANT_ID } from '../../../workless/tenant/tenant.constants';
import { ContactStatus, CrmContactEntity } from '../entities/crm-contact.entity';
import { CrmContactRepository } from '../repositories/crm-contact.repository';

@Injectable()
export class CrmContactSeeder implements ModuleSeeder {
  readonly name = 'crm-default-contacts';
  readonly order = 100;

  constructor(private readonly repository: CrmContactRepository) {}

  async seed(): Promise<void> {
    const existing = await this.repository.countAll();
    if (existing > 0) {
      return;
    }

    const seedContacts = this.createSeedContacts();
    await this.repository.saveMany(seedContacts);
  }

  private createSeedContacts(): CrmContactEntity[] {
    return [
      this.createContact(
        '00000000-0000-0000-0000-000000000001',
        'Acme Procurement Team',
        'procurement@acme.example',
        '+1-555-1000',
        ContactStatus.LEAD,
      ),
      this.createContact(
        '00000000-0000-0000-0000-000000000001',
        'Globex Support Director',
        'support.director@globex.example',
        '+1-555-1001',
        ContactStatus.CUSTOMER,
      ),
    ];
  }

  private createContact(
    orgId: string,
    fullName: string,
    email: string,
    phone: string,
    status: ContactStatus,
  ): CrmContactEntity {
    const contact = new CrmContactEntity();
    contact.tenantId = DEFAULT_TENANT_ID;
    contact.orgId = orgId;
    contact.fullName = fullName;
    contact.email = email;
    contact.phone = phone;
    contact.status = status;
    return contact;
  }
}
