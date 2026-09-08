export interface CompanyContextPort {
  getCompanyId(): string | null;
  requireCompanyId(): string;
}

export const COMPANY_CONTEXT = Symbol('COMPANY_CONTEXT');
