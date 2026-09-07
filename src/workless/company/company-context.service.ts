import { Injectable } from '@nestjs/common';
import { AsyncLocalStorage } from 'node:async_hooks';
import { DEFAULT_COMPANY_ID, normalizeCompanyId } from '@/workless/company/company.constants';
import { CompanyContextPort } from '@/workless/company/company-context.interface';

type CompanyStore = {
  companyId: string;
};

@Injectable()
export class CompanyContextService implements CompanyContextPort {
  private readonly als = new AsyncLocalStorage<CompanyStore>();

  run(companyId: string, callback: () => void): void {
    this.als.run({ companyId: normalizeCompanyId(companyId) }, callback);
  }

  activate(companyId: string): void {
    const store = this.als.getStore();
    if (!store) {
      throw new Error('Company context has not been initialized for this request.');
    }

    store.companyId = normalizeCompanyId(companyId);
  }

  getCompanyId(): string {
    return this.als.getStore()?.companyId ?? DEFAULT_COMPANY_ID;
  }
}
