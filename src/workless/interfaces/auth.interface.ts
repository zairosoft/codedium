import type { MembershipRecord } from './user.interface';

export type JwtPayload = {
  userId: string;
  email: string;
  tenantId: string;
  roles: string[];
};

export type AuthenticatedUser = {
  userId: string;
  email: string;
  tenantId: string;
  roles: string[];
  memberships: MembershipRecord[];
};

export type AuthSession = {
  userId: string;
  tenantId?: string;
  authenticatedAt: Date;
};

export type LoginResult = {
  accessToken: string;
  user: {
    id: string;
    email: string;
    displayName: string;
    roles: string[];
  };
};

export interface AuthServicePort {
  createSession(userId: string, tenantId?: string): Promise<AuthSession>;
  login(email: string, tenantId?: string): Promise<LoginResult>;
}

export const AUTH_SERVICE = Symbol('AUTH_SERVICE');
