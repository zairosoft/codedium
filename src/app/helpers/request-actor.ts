import { Request } from 'express';
import type { AuthenticatedUser } from '../interfaces/auth.interface';

export type RequestActor = {
  userId?: string;
  roleCodes: string[];
  companyId?: string;
  organizationId?: string;
};

export type PermissionAwareRequest = Request & {
  actor?: RequestActor;
  user?: AuthenticatedUser;
};

/**
 * Resolves the request actor from the authenticated JWT user attached
 * to the request by Passport. Falls back to empty actor if no user
 * is present (public routes).
 */
export function resolveRequestActor(request: PermissionAwareRequest): RequestActor {
  const authenticatedUser = request.user;

  if (authenticatedUser) {
    const activeMembership = authenticatedUser.memberships?.find(
      (membership) => membership.isDefault,
    ) ?? authenticatedUser.memberships?.[0];

    return {
      userId: authenticatedUser.userId,
      companyId: activeMembership?.companyId,
      organizationId: activeMembership?.organizationId,
      roleCodes: [...new Set(authenticatedUser.roles ?? [])],
    };
  }

  // Fallback for public routes — no identity
  return {
    userId: undefined,
    companyId: undefined,
    organizationId: undefined,
    roleCodes: [],
  };
}
