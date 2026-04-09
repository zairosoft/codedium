import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { ModuleSeeder } from '../../../core/lifecycle/module-seeder.interface';
import { DEFAULT_TENANT_ID } from '../../../core/tenant/tenant.constants';
import { ContactStatus, CrmContactEntity } from '../entities/crm-contact.entity';

@Injectable()
export class CrmContactSeeder implements ModuleSeeder {
  constructor(
    @InjectRepository(CrmContactEntity)
    private readonly repository: Repository<CrmContactEntity>,
  ) {}

  async seed(): Promise<void> {
    const tenantId = DEFAULT_TENANT_ID;
    const existing = await this.repository.count({ where: { tenantId } });
    if (existing > 0) {
      return;
    }

    const seedContacts = this.repository.create([
      {
        tenantId,
        orgId: '00000000-0000-0000-0000-000000000001',
        fullName: 'Acme Procurement Team',
        email: 'procurement@acme.example',
        phone: '+1-555-1000',
        status: ContactStatus.LEAD,
      },
      {
        tenantId,
        orgId: '00000000-0000-0000-0000-000000000001',
        fullName: 'Globex Support Director',
        email: 'support.director@globex.example',
        phone: '+1-555-1001',
        status: ContactStatus.CUSTOMER,
      },
    ]);

    await this.repository.save(seedContacts);
  }
}
