export type PermissionRecord = {
  code: string;
  resource: string;
  action: string;
  description?: string;
};

export interface PermissionServicePort {
  listForRole(roleCode: string): PermissionRecord[];
  expand(roleCodes: string[]): PermissionRecord[];
  hasPermissions(roleCodes: string[], requiredPermissions: string[]): boolean;
}

export const PERMISSION_SERVICE = Symbol('PERMISSION_SERVICE');
