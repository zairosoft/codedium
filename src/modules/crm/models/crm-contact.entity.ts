import { Column, Entity, Index } from 'typeorm';
import { TenantScopedEntity } from '../../../common/database/tenant-scoped.entity';

export enum ContactStatus {
  LEAD = 'lead',
  CUSTOMER = 'customer',
}

export type CrmContactDraft = {
  orgId: string;
  fullName: string;
  email: string;
  phone?: string | null;
  status?: ContactStatus;
};

export type CrmContactPatch = Partial<CrmContactDraft>;

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

  static createForTenant(tenantId: string, draft: CrmContactDraft): CrmContactEntity {
    const entity = new CrmContactEntity();
    entity.tenantId = tenantId;
    entity.orgId = draft.orgId;
    entity.applyProfile(draft);
    return entity;
  }

  applyProfile(patch: CrmContactPatch): void {
    if (patch.fullName !== undefined) {
      this.fullName = patch.fullName.trim();
    }

    if (patch.email !== undefined) {
      this.email = patch.email.trim().toLowerCase();
    }

    if (patch.phone !== undefined) {
      this.phone = patch.phone?.trim() || null;
    }

    if (patch.orgId !== undefined) {
      this.orgId = patch.orgId;
    }

    if (patch.status !== undefined) {
      this.status = patch.status;
    }

    if (!this.status) {
      this.status = ContactStatus.LEAD;
    }
  }

  promoteToCustomer(): void {
    this.status = ContactStatus.CUSTOMER;
  }

  isCustomer(): boolean {
    return this.status === ContactStatus.CUSTOMER;
  }
}

