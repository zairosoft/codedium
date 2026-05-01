import { Inject, Injectable, UnauthorizedException } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import { PassportStrategy } from '@nestjs/passport';
import { ExtractJwt, Strategy } from 'passport-jwt';
import { USER_SERVICE, UserServicePort } from '../../workless/interfaces/user.interface';
import { TENANT_CONTEXT, TenantContextPort } from '../../workless/tenant/tenant-context.interface';
import type { JwtPayload, AuthenticatedUser } from '../../workless/interfaces/auth.interface';

@Injectable()
export class JwtStrategy extends PassportStrategy(Strategy) {
  constructor(
    configService: ConfigService,
    @Inject(USER_SERVICE)
    private readonly userService: UserServicePort,
    @Inject(TENANT_CONTEXT)
    private readonly tenantContext: TenantContextPort,
  ) {
    super({
      jwtFromRequest: ExtractJwt.fromAuthHeaderAsBearerToken(),
      ignoreExpiration: false,
      secretOrKey: configService.get<string>('JWT_SECRET', 'dev-secret-change-me'),
    });
  }

  /**
   * Passport calls this after decoding and verifying the JWT signature.
   * Return value is attached to `request.user`.
   */
  async validate(payload: JwtPayload): Promise<AuthenticatedUser> {
    const user = await this.userService.findById(payload.userId);
    if (!user || !user.active) {
      throw new UnauthorizedException('User account is disabled or not found.');
    }

    return {
      userId: user.id,
      email: user.email,
      tenantId: payload.tenantId,
      roles: user.roles,
      memberships: user.memberships,
    };
  }
}
