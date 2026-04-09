export type UserModel = {
  id: string;
  email: string;
  displayName: string;
  active: boolean;
  roles: string[];
  createdAt: Date;
  updatedAt: Date;
};
