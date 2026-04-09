export type PermissionRecord = {
  resource: string;
  actions: string[];
};

export interface PermissionServicePort {
  listForRole(roleCode: string): PermissionRecord[];
}

export const PERMISSION_SERVICE = Symbol('PERMISSION_SERVICE');
