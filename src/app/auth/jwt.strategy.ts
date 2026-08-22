import { Injectable, UnauthorizedException } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import { PassportStrategy } from '@nestjs/passport';
import { InjectRepository } from '@nestjs/typeorm';
import { ExtractJwt, Strategy } from 'passport-jwt';
import { Repository } from 'typeorm';
import type { JwtPayload, AuthenticatedUser } from '../../workless/interfaces/auth.interface';
import { PlatformUserEntity } from '../entities/platform-user.entity';
import { resolveJwtSecret } from '../../config/jwt.config';

@Injectable()
export class JwtStrategy extends PassportStrategy(Strategy) {
  constructor(
    configService: ConfigService,
    @InjectRepository(PlatformUserEntity)
    private readonly usersRepository: Repository<PlatformUserEntity>,
  ) {
    super({
      jwtFromRequest: ExtractJwt.fromAuthHeaderAsBearerToken(),
      ignoreExpiration: false,
      secretOrKey: resolveJwtSecret(configService),
    });
  }

  /**
   * Passport calls this after decoding and verifying the JWT signature.
   * Return value is attached to `request.user`.
   */
  async validate(payload: JwtPayload): Promise<AuthenticatedUser> {
    const user = await this.usersRepository.findOne({
      where: { id: payload.userId, isActive: true },
      relations: { memberships: true },
    });
    if (!user) {
      throw new UnauthorizedException('User account is disabled or not found.');
    }

    return {
      userId: user.id,
      email: user.email,
      tenantId: payload.tenantId,
      roles: [user.role],
      memberships: (user.memberships ?? []).map((membership) => ({
        organizationId: membership.organizationId,
        roleCode: membership.roleCode,
        isDefault: membership.isDefault,
      })),
    };
  }
}
