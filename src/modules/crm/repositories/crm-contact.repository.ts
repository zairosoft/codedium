import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { ILike, Repository } from 'typeorm';
import { TenantContextService } from '../../../common/tenant/tenant-context.service';
import { CreateContactDto } from '../dto/create-contact.dto';
import { ListContactsDto } from '../dto/list-contacts.dto';
import { UpdateContactDto } from '../dto/update-contact.dto';
import { ContactStatus, CrmContactEntity } from '../models/crm-contact.entity';

export type CrmDashboardSummary = {
  tenantId: string;
  totalContacts: number;
  totalCustomers: number;
  totalLeads: number;
  recentContacts: CrmContactEntity[];
};

@Injectable()
export class CrmContactRepository {
  constructor(
    @InjectRepository(CrmContactEntity)
    private readonly repository: Repository<CrmContactEntity>,
    private readonly tenantContext: TenantContextService,
  ) {}

  async create(dto: CreateContactDto): Promise<CrmContactEntity> {
    const entity = CrmContactEntity.createForTenant(this.tenantContext.getTenantId(), dto);
    return this.repository.save(entity);
  }

  async findAll(query: ListContactsDto): Promise<[CrmContactEntity[], number]> {
    const tenantId = this.tenantContext.getTenantId();
    const page = query.page ?? 1;
    const limit = query.limit ?? 20;
    const search = query.search?.trim();
    const where = search
      ? [
          { tenantId, fullName: ILike(`%${search}%`) },
          { tenantId, email: ILike(`%${search}%`) },
        ]
      : { tenantId };

    return this.repository.findAndCount({
      select: {
        id: true,
        fullName: true,
        email: true,
        phone: true,
        status: true,
        createdAt: true,
      },
      where,
      order: { createdAt: 'DESC' },
      skip: (page - 1) * limit,
      take: limit,
    });
  }

  async findById(id: string): Promise<CrmContactEntity> {
    const tenantId = this.tenantContext.getTenantId();
    const found = await this.repository.findOne({
      where: { id, tenantId },
    });

    if (!found) {
      throw new NotFoundException('CRM contact not found');
    }

    return found;
  }

  async update(id: string, dto: UpdateContactDto): Promise<CrmContactEntity> {
    const found = await this.findById(id);
    found.applyProfile(dto);
    return this.repository.save(found);
  }

  async remove(id: string): Promise<void> {
    const tenantId = this.tenantContext.getTenantId();
    const result = await this.repository.softDelete({ id, tenantId });
    if (!result.affected) {
      throw new NotFoundException('CRM contact not found');
    }
  }

  async getDashboardSummary(): Promise<CrmDashboardSummary> {
    const tenantId = this.tenantContext.getTenantId();
    const [totalContacts, totalCustomers, totalLeads, recentContacts] =
      await Promise.all([
        this.repository.count({ where: { tenantId } }),
        this.repository.count({
          where: { tenantId, status: ContactStatus.CUSTOMER },
        }),
        this.repository.count({
          where: { tenantId, status: ContactStatus.LEAD },
        }),
        this.repository.find({
          select: {
            id: true,
            fullName: true,
            email: true,
            phone: true,
            status: true,
            createdAt: true,
          },
          where: { tenantId },
          order: { createdAt: 'DESC' },
          take: 5,
        }),
      ]);

    return {
      tenantId,
      totalContacts,
      totalCustomers,
      totalLeads,
      recentContacts,
    };
  }

  getTenantId(): string {
    return this.tenantContext.getTenantId();
  }
}
