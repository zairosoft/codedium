import { Request } from 'express';

export type RequestActor = {
  userId?: string;
  roleCodes: string[];
  organizationId?: string;
};

export type PermissionAwareRequest = Request & {
  actor?: RequestActor;
};

export function resolveRequestActor(request: Request): RequestActor {
  const rawRoles = request.headers['x-user-roles'];
  const rawOrganizationRole = request.headers['x-organization-role'];
  const rawUserId = request.headers['x-user-id'];
  const rawOrganizationId = request.headers['x-organization-id'];

  const roleCodes = parseHeaderList(rawRoles);
  const organizationRoleCodes = parseHeaderList(rawOrganizationRole);
  const userId = extractHeaderValue(rawUserId);
  const organizationId = extractHeaderValue(rawOrganizationId);

  return {
    userId: userId || undefined,
    organizationId: organizationId || undefined,
    roleCodes: [...new Set([...roleCodes, ...organizationRoleCodes])],
  };
}

function parseHeaderList(value: string | string[] | undefined): string[] {
  return extractHeaderValue(value)
    .split(',')
    .map((segment) => segment.trim())
    .filter(Boolean);
}

function extractHeaderValue(value: string | string[] | undefined): string {
  if (Array.isArray(value)) {
    return value[0] ?? '';
  }

  return value ?? '';
}
