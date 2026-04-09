export type AuthSession = {
  userId: string;
  tenantId?: string;
  authenticatedAt: Date;
};

export interface AuthServicePort {
  createSession(userId: string, tenantId?: string): Promise<AuthSession>;
}

export const AUTH_SERVICE = Symbol('AUTH_SERVICE');
