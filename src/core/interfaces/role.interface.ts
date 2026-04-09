export type RoleRecord = {
  code: string;
  description: string;
};

export interface RoleServicePort {
  list(): RoleRecord[];
}

export const ROLE_SERVICE = Symbol('ROLE_SERVICE');
