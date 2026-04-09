export const DEFAULT_TENANT_ID = '00000000-0000-0000-0000-000000000000';

const UUID_PATTERN =
  /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i;

export function normalizeTenantId(rawTenantId?: string): string {
  if (!rawTenantId || rawTenantId === 'public') {
    return DEFAULT_TENANT_ID;
  }

  const tenantId = rawTenantId.trim();
  if (!UUID_PATTERN.test(tenantId)) {
    throw new Error(`Tenant id "${rawTenantId}" is not a valid UUID.`);
  }

  return tenantId;
}
