export type UserRecord = {
  id: string;
  email: string;
  displayName: string;
  active: boolean;
  roles: string[];
  createdAt: Date;
  updatedAt: Date;
};

export type CreateUserInput = {
  email: string;
  displayName: string;
  roles?: string[];
};

export type UpdateUserInput = Partial<{
  email: string;
  displayName: string;
  active: boolean;
  roles: string[];
}>;

export interface UserServicePort {
  findById(id: string): Promise<UserRecord | null>;
  createUser(input: CreateUserInput): Promise<UserRecord>;
  updateUser(id: string, input: UpdateUserInput): Promise<UserRecord>;
}

export const USER_SERVICE = Symbol('USER_SERVICE');
