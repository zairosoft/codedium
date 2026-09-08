import { Injectable, UnauthorizedException } from '@nestjs/common';
import { AsyncLocalStorage } from 'node:async_hooks';
import { isUUID } from 'class-validator';
import { CompanyContextPort } from '@/app/interfaces/company-context.interface';

type CompanyStore = {
  companyId: string | null;
};

@Injectable()
export class RequestCompanyContextService implements CompanyContextPort {
  private readonly storage = new AsyncLocalStorage<CompanyStore>();

  run(callback: () => void): void {
    this.storage.run({ companyId: null }, callback);
  }

  activate(companyId: string): void {
    const store = this.storage.getStore();
    if (!store) {
      throw new Error('Company context has not been initialized for this request.');
    }

    const normalizedCompanyId = companyId.trim().toLowerCase();
    if (!isUUID(normalizedCompanyId)) {
      throw new Error(`Company id "${companyId}" is not a valid UUID.`);
    }

    store.companyId = normalizedCompanyId;
  }

  getCompanyId(): string | null {
    return this.storage.getStore()?.companyId ?? null;
  }

  requireCompanyId(): string {
    const companyId = this.getCompanyId();
    if (!companyId) {
      throw new UnauthorizedException('Company context is required.');
    }

    return companyId;
  }
}
