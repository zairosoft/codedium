export type JwtPayload = {
  userId: string;
  email: string;
  companyId: string;
  roles: string[];
};

export type AuthenticatedUser = {
  userId: string;
  email: string;
  companyId: string;
  roles: string[];
};

export type AuthSession = {
  userId: string;
  companyId: string;
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
  createSession(userId: string): Promise<AuthSession>;
  login(email: string, password: string): Promise<LoginResult>;
}

export const AUTH_SERVICE = Symbol('AUTH_SERVICE');
