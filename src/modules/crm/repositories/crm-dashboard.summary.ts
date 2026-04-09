import { CrmContactEntity } from '../entities/crm-contact.entity';

export type CrmDashboardSummary = {
  tenantId: string;
  totalContacts: number;
  totalCustomers: number;
  totalLeads: number;
  recentContacts: CrmContactEntity[];
};
