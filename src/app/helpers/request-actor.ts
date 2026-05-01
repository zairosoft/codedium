import { Request } from 'express';
import type { AuthenticatedUser } from '../../workless/interfaces/auth.interface';

export type RequestActor = {
  userId?: string;
  roleCodes: string[];
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
    return {
      userId: authenticatedUser.userId,
      organizationId: authenticatedUser.memberships?.[0]?.organizationId,
      roleCodes: [...new Set(authenticatedUser.roles ?? [])],
    };
  }

  // Fallback for public routes — no identity
  return {
    userId: undefined,
    organizationId: undefined,
    roleCodes: [],
  };
}
