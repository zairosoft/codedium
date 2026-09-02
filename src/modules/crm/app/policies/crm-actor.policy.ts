import { Request } from 'express';

export type CrmActor = {
  permissionCodes: string[];
};

export function resolveCrmActor(request: Request): CrmActor {
  const rawPermissions = request.headers['x-permissions'];
  const headerValue = Array.isArray(rawPermissions) ? rawPermissions[0] : rawPermissions;

  return {
    permissionCodes: (headerValue ?? '')
      .split(',')
      .map((permission) => permission.trim())
      .filter(Boolean),
  };
}
