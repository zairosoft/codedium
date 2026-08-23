export type MembershipRecord = {
  companyId: string;
  organizationId: string;
  roleCode: string;
  isDefault?: boolean;
};

export type UserRecord = {
  id: string;
  companyId: string;
  email: string;
  displayName: string;
  active: boolean;
  roles: string[];
  memberships: MembershipRecord[];
  createdAt: Date;
  updatedAt: Date;
};

export type CreateUserInput = {
  email: string;
  displayName: string;
  roles?: string[];
  memberships?: MembershipRecord[];
};

export type UpdateUserInput = Partial<{
  email: string;
  displayName: string;
  active: boolean;
  roles: string[];
  memberships: MembershipRecord[];
}>;

export interface UserServicePort {
  findById(id: string): Promise<UserRecord | null>;
  findByEmail(email: string): Promise<UserRecord | null>;
  createUser(input: CreateUserInput): Promise<UserRecord>;
  updateUser(id: string, input: UpdateUserInput): Promise<UserRecord>;
}

export const USER_SERVICE = Symbol('USER_SERVICE');
