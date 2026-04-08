import { Column, Entity, Index } from 'typeorm';
import { TenantScopedEntity } from '../../../common/database/tenant-scoped.entity';

export enum ContactStatus {
  LEAD = 'lead',
  CUSTOMER = 'customer',
}

@Entity({ name: 'crm_contacts' })
@Index(['tenantId', 'email'], { unique: true })
export class CrmContactEntity extends TenantScopedEntity {
  @Column({ type: 'uuid' })
  orgId: string;

  @Column({ type: 'varchar', length: 120 })
  fullName: string;

  @Column({ type: 'varchar', length: 160 })
  email: string;

  @Column({ type: 'varchar', length: 30, nullable: true })
  phone?: string | null;

  @Column({
    type: 'enum',
    enum: ContactStatus,
    default: ContactStatus.LEAD,
  })
  status: ContactStatus;
}

