import { Injectable } from '@nestjs/common';
import { AsyncLocalStorage } from 'node:async_hooks';

type TenantStore = {
  tenantId: string;
};

@Injectable()
export class TenantContextService {
  private readonly als = new AsyncLocalStorage<TenantStore>();

  run(tenantId: string, callback: () => void): void {
    this.als.run({ tenantId }, callback);
  }

  getTenantId(): string {
    return this.als.getStore()?.tenantId ?? 'public';
  }
}


