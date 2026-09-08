export type CompanyRecord = {
  id: string;
  name: string;
  code: string;
  description?: string | null;
  logo?: string | null;
  isActive: boolean;
  createdAt: Date;
  updatedAt: Date;
};

export type CreateCompanyInput = {
  name: string;
  code: string;
  description?: string;
  logo?: string;
  isActive?: boolean;
};

export type UpdateCompanyInput = Partial<CreateCompanyInput>;
