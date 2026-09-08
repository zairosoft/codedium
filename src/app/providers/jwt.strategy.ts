import { Injectable, UnauthorizedException } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import { PassportStrategy } from '@nestjs/passport';
import { InjectRepository } from '@nestjs/typeorm';
import { ExtractJwt, Strategy } from 'passport-jwt';
import { Repository } from 'typeorm';
import { PlatformUserEntity } from '@/app/entities/user.entity';
import type { AuthenticatedUser, JwtPayload } from '@/app/interfaces/auth.interface';
import { resolveJwtSecret } from '@/config/jwt.config';
import { normalizeCompanyId } from '@/workless/company/company.constants';

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

  async validate(payload: JwtPayload): Promise<AuthenticatedUser> {
    const user = await this.usersRepository.findOne({
      where: { id: payload.userId, isActive: true },
    });
    if (!user) {
      throw new UnauthorizedException('User account is disabled or not found.');
    }

    if (typeof payload.companyId !== 'string') {
      throw new UnauthorizedException('Token company is invalid.');
    }

    let tokenCompanyId: string;
    try {
      tokenCompanyId = normalizeCompanyId(payload.companyId);
    } catch {
      throw new UnauthorizedException('Token company is invalid.');
    }

    const databaseCompanyId = normalizeCompanyId(user.companyId);
    if (tokenCompanyId !== databaseCompanyId) {
      throw new UnauthorizedException('Token company is no longer valid.');
    }

    return {
      userId: user.id,
      email: user.email,
      companyId: databaseCompanyId,
      roles: [user.role],
    };
  }
}
