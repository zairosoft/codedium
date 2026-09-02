import { Injectable } from '@nestjs/common';
import { AsyncLocalStorage } from 'node:async_hooks';
import { DEFAULT_TENANT_ID, normalizeTenantId } from '@/workless/tenant/tenant.constants';
import { TenantContextPort } from '@/workless/tenant/tenant-context.interface';

type TenantStore = {
  tenantId: string;
};

@Injectable()
export class TenantContextService implements TenantContextPort {
  private readonly als = new AsyncLocalStorage<TenantStore>();

  run(tenantId: string, callback: () => void): void {
    this.als.run({ tenantId: normalizeTenantId(tenantId) }, callback);
  }

  getTenantId(): string {
    return this.als.getStore()?.tenantId ?? DEFAULT_TENANT_ID;
  }
}
