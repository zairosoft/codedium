import { ContactStatus, CrmContactEntity } from '../entities/crm-contact.entity';

export { ContactStatus };

export type CrmContactDraft = {
  orgId: string;
  fullName: string;
  email: string;
  phone?: string | null;
  status?: ContactStatus;
};

export type CrmContactPatch = Partial<CrmContactDraft>;

type CrmContactState = {
  id?: string;
  tenantId: string;
  orgId: string;
  fullName: string;
  email: string;
  phone?: string | null;
  status: ContactStatus;
  createdAt?: Date;
  updatedAt?: Date;
  deletedAt?: Date | null;
};

export class CrmContactModel {
  private constructor(private readonly state: CrmContactState) {}

  static createForTenant(tenantId: string, draft: CrmContactDraft): CrmContactModel {
    const model = new CrmContactModel({
      tenantId,
      orgId: draft.orgId,
      fullName: '',
      email: '',
      phone: null,
      status: ContactStatus.LEAD,
    });

    model.applyProfile(draft);
    return model;
  }

  static fromEntity(entity: CrmContactEntity): CrmContactModel {
    return new CrmContactModel({
      id: entity.id,
      tenantId: entity.tenantId,
      orgId: entity.orgId,
      fullName: entity.fullName,
      email: entity.email,
      phone: entity.phone,
      status: entity.status,
      createdAt: entity.createdAt,
      updatedAt: entity.updatedAt,
      deletedAt: entity.deletedAt,
    });
  }

  applyProfile(patch: CrmContactPatch): void {
    if (patch.fullName !== undefined) {
      this.state.fullName = patch.fullName.trim();
    }

    if (patch.email !== undefined) {
      this.state.email = patch.email.trim().toLowerCase();
    }

    if (patch.phone !== undefined) {
      this.state.phone = patch.phone?.trim() || null;
    }

    if (patch.orgId !== undefined) {
      this.state.orgId = patch.orgId;
    }

    if (patch.status !== undefined) {
      this.state.status = patch.status;
    }
  }

  promoteToCustomer(): void {
    this.state.status = ContactStatus.CUSTOMER;
  }

  isCustomer(): boolean {
    return this.state.status === ContactStatus.CUSTOMER;
  }

  toEntity(target: CrmContactEntity = new CrmContactEntity()): CrmContactEntity {
    target.id = this.state.id ?? target.id;
    target.tenantId = this.state.tenantId;
    target.orgId = this.state.orgId;
    target.fullName = this.state.fullName;
    target.email = this.state.email;
    target.phone = this.state.phone;
    target.status = this.state.status;
    target.createdAt = this.state.createdAt ?? target.createdAt;
    target.updatedAt = this.state.updatedAt ?? target.updatedAt;
    target.deletedAt = this.state.deletedAt ?? target.deletedAt;
    return target;
  }
}
