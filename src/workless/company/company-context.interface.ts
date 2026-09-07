export interface CompanyContextPort {
  getCompanyId(): string;
}

export const COMPANY_CONTEXT = Symbol('COMPANY_CONTEXT');
