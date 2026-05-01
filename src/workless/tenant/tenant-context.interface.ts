export interface TenantContextPort {
  getTenantId(): string;
}

export const TENANT_CONTEXT = Symbol('TENANT_CONTEXT');
