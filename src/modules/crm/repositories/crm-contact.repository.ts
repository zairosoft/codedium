import { Inject, Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { ILike, Repository } from 'typeorm';
import { TENANT_CONTEXT, TenantContextPort } from '../../../workless/tenant/tenant-context.interface';
import { ListContactsDto } from '../dto/list-contacts.dto';
import { ContactStatus, CrmContactEntity } from '../entities/crm-contact.entity';
import { CrmDashboardSummary } from './crm-dashboard.summary';

@Injectable()
export class CrmContactRepository {
  constructor(
    @InjectRepository(CrmContactEntity)
    private readonly repository: Repository<CrmContactEntity>,
    @Inject(TENANT_CONTEXT)
    private readonly tenantContext: TenantContextPort,
  ) {}

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

  async findByIdOrFail(id: string): Promise<CrmContactEntity> {
    const tenantId = this.tenantContext.getTenantId();
    const found = await this.repository.findOne({
      where: { id, tenantId },
    });

    if (!found) {
      throw new NotFoundException('CRM contact not found');
    }

    return found;
  }

  async save(entity: CrmContactEntity): Promise<CrmContactEntity> {
    return this.repository.save(entity);
  }

  async saveMany(entities: CrmContactEntity[]): Promise<CrmContactEntity[]> {
    return this.repository.save(entities);
  }

  async deleteById(id: string): Promise<void> {
    const tenantId = this.tenantContext.getTenantId();
    const result = await this.repository.softDelete({ id, tenantId });
    if (!result.affected) {
      throw new NotFoundException('CRM contact not found');
    }
  }

  async countAll(): Promise<number> {
    const tenantId = this.tenantContext.getTenantId();
    return this.repository.count({ where: { tenantId } });
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
