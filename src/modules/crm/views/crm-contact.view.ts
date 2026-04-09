import {
  CrmContactEntity,
} from '../entities/crm-contact.entity';
import { CrmDashboardSummary } from '../models/crm-dashboard.model';
import { ContactStatus } from '../models/crm-contact.model';

export type CrmContactView = {
  id: string;
  fullName: string;
  email: string;
  phone?: string | null;
  status: string;
  createdAt: string;
};

export type CrmDashboardView = {
  tenantId: string;
  metrics: {
    totalContacts: number;
    totalCustomers: number;
    totalLeads: number;
  };
  recentContacts: CrmContactView[];
};

export class CrmContactViewMapper {
  static toView(entity: CrmContactEntity): CrmContactView {
    return {
      id: entity.id,
      fullName: entity.fullName,
      email: entity.email,
      phone: entity.phone,
      status: entity.status,
      createdAt: entity.createdAt.toISOString(),
    };
  }

  static toList(entities: CrmContactEntity[]): CrmContactView[] {
    return entities.map((entity) => this.toView(entity));
  }

  static toDashboard(summary: CrmDashboardSummary): CrmDashboardView {
    return {
      tenantId: summary.tenantId,
      metrics: {
        totalContacts: summary.totalContacts,
        totalCustomers: summary.totalCustomers,
        totalLeads: summary.totalLeads,
      },
      recentContacts: summary.recentContacts.map((contact) => ({
        ...this.toView(contact),
        status:
          contact.status === ContactStatus.CUSTOMER ? 'customer' : 'lead',
      })),
    };
  }
}
