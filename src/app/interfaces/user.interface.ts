import { UserModel } from '../users/models/user.model';

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
  findById(id: string): Promise<UserModel | null>;
  createUser(input: CreateUserInput): Promise<UserModel>;
  updateUser(id: string, input: UpdateUserInput): Promise<UserModel>;
}

export const USER_SERVICE = Symbol('USER_SERVICE');
