import {
  CrmContactEntity,
} from '../entities/crm-contact.entity';
import { ContactStatus } from '../entities/crm-contact.entity';
import { CrmDashboardSummary } from '../repositories/crm-dashboard.summary';

export type CrmContactView = {
  id: string;
  fullName: string;
  email: string;
  phone?: string | null;
  status: string;
  createdAt: string;
};

export type CrmDashboardView = {
  type: 'dashboard';
  resource: 'crm.contact';
  tenantId: string;
  metrics: {
    totalContacts: number;
    totalCustomers: number;
    totalLeads: number;
  };
  recentContacts: CrmContactView[];
};

export type CrmContactsTableView = {
  type: 'table';
  resource: 'crm.contact';
  columns: { key: string; label: string }[];
  data: CrmContactView[];
  meta: {
    total: number;
    page: number;
    limit: number;
  };
};

export type CrmContactDetailView = {
  type: 'detail';
  resource: 'crm.contact';
  data: CrmContactView;
};

export class CrmContactViewMapper {
  static toView(entity: CrmContactEntity): CrmContactView {
    return {
      id: entity.id,
      fullName: entity.fullName,
      email: entity.email,
      phone: entity.phone,
      status: entity.status,
      createdAt: this.serializeDate(entity.createdAt),
    };
  }

  static toList(entities: CrmContactEntity[]): CrmContactView[] {
    return entities.map((entity) => this.toView(entity));
  }

  static toTableSchema(input: {
    contacts: CrmContactEntity[];
    total: number;
    page: number;
    limit: number;
  }): CrmContactsTableView {
    return {
      type: 'table',
      resource: 'crm.contact',
      columns: [
        { key: 'fullName', label: 'Full Name' },
        { key: 'email', label: 'Email' },
        { key: 'phone', label: 'Phone' },
        { key: 'status', label: 'Status' },
        { key: 'createdAt', label: 'Created At' },
      ],
      data: this.toList(input.contacts),
      meta: {
        total: input.total,
        page: input.page,
        limit: input.limit,
      },
    };
  }

  static toDetailSchema(entity: CrmContactEntity): CrmContactDetailView {
    return {
      type: 'detail',
      resource: 'crm.contact',
      data: this.toView(entity),
    };
  }

  static toDashboard(summary: CrmDashboardSummary): CrmDashboardView {
    return {
      type: 'dashboard',
      resource: 'crm.contact',
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

  private static serializeDate(value: Date | string): string {
    return value instanceof Date ? value.toISOString() : new Date(value).toISOString();
  }
}
