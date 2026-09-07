export const DEFAULT_COMPANY_ID = '00000000-0000-0000-0000-000000000000';

const UUID_PATTERN =
  /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i;

export function normalizeCompanyId(rawCompanyId?: string): string {
  if (!rawCompanyId) {
    return DEFAULT_COMPANY_ID;
  }

  const companyId = rawCompanyId.trim();
  if (!UUID_PATTERN.test(companyId)) {
    throw new Error(`Company id "${rawCompanyId}" is not a valid UUID.`);
  }

  return companyId.toLowerCase();
}
