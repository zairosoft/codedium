import { Inject, Injectable, UnauthorizedException } from '@nestjs/common';
import { JwtService } from '@nestjs/jwt';
import {
  AuthServicePort,
  AuthSession,
  JwtPayload,
  LoginResult,
} from '../../core/interfaces/auth.interface';
import { USER_SERVICE, UserServicePort } from '../../core/interfaces/user.interface';
import { DEFAULT_TENANT_ID } from '../../core/tenant/tenant.constants';

@Injectable()
export class AuthService implements AuthServicePort {
  constructor(
    @Inject(USER_SERVICE) private readonly users: UserServicePort,
    private readonly jwtService: JwtService,
  ) {}

  async createSession(userId: string, tenantId?: string): Promise<AuthSession> {
    const user = await this.users.findById(userId);
    if (!user || !user.active) {
      throw new UnauthorizedException('User is not available for authentication.');
    }

    return {
      userId: user.id,
      tenantId,
      authenticatedAt: new Date(),
    };
  }

  async login(email: string, tenantId?: string): Promise<LoginResult> {
    const normalizedEmail = email.trim().toLowerCase();
    const resolvedTenantId = tenantId ?? DEFAULT_TENANT_ID;

    const user = await this.users.findByEmail(normalizedEmail);
    if (!user || !user.active) {
      throw new UnauthorizedException('Invalid credentials.');
    }

    const payload: JwtPayload = {
      userId: user.id,
      email: user.email,
      tenantId: resolvedTenantId,
      roles: user.roles,
    };

    const accessToken = this.jwtService.sign(payload);

    return {
      accessToken,
      user: {
        id: user.id,
        email: user.email,
        displayName: user.displayName,
        roles: user.roles,
      },
    };
  }
}
