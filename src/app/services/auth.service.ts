import { randomBytes, scryptSync, timingSafeEqual } from 'node:crypto';
import { ConflictException, Injectable, UnauthorizedException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { JwtService } from '@nestjs/jwt';
import { Repository } from 'typeorm';
import {
  AuthServicePort,
  AuthSession,
  JwtPayload,
  LoginResult,
} from '../../workless/interfaces/auth.interface';
import { DEFAULT_TENANT_ID } from '../../workless/tenant/tenant.constants';
import { RegisterDto } from '../dto/register.dto';
import { PlatformUserEntity } from '../entities/platform-user.entity';

@Injectable()
export class AuthService implements AuthServicePort {
  constructor(
    @InjectRepository(PlatformUserEntity)
    private readonly usersRepository: Repository<PlatformUserEntity>,
    private readonly jwtService: JwtService,
  ) {}

  async createSession(userId: string, tenantId?: string): Promise<AuthSession> {
    const user = await this.usersRepository.findOne({
      where: { id: userId, isActive: true },
    });
    if (!user) {
      throw new UnauthorizedException('User is not available for authentication.');
    }

    return {
      userId: user.id,
      tenantId,
      authenticatedAt: new Date(),
    };
  }

  async login(email: string, password: string, tenantId?: string): Promise<LoginResult> {
    const normalizedEmail = email.trim().toLowerCase();
    const user = await this.usersRepository
      .createQueryBuilder('user')
      .addSelect('user.password')
      .where('user.email = :email', { email: normalizedEmail })
      .getOne();

    if (!user || !user.isActive || !this.verifyPassword(password, user.password)) {
      throw new UnauthorizedException('Invalid email or password.');
    }

    await this.usersRepository.update(user.id, { lastLoggedAt: new Date() });
    return this.createLoginResult(user, tenantId);
  }

  async register(dto: RegisterDto): Promise<LoginResult> {
    const email = dto.email.trim().toLowerCase();
    const existing = await this.usersRepository.findOne({ where: { email } });
    if (existing) {
      throw new ConflictException(`User email "${email}" already exists.`);
    }

    const user = this.usersRepository.create({
      name: dto.displayName.trim(),
      email,
      password: this.hashPassword(dto.password),
      role: 'user',
      isActive: true,
      locale: 'en',
    });

    const created = await this.usersRepository.save(user);
    return this.createLoginResult(created);
  }

  private createLoginResult(user: PlatformUserEntity, tenantId?: string): LoginResult {
    const payload: JwtPayload = {
      userId: user.id,
      email: user.email,
      tenantId: tenantId ?? DEFAULT_TENANT_ID,
      roles: [user.role],
    };

    return {
      accessToken: this.jwtService.sign(payload),
      user: {
        id: user.id,
        email: user.email,
        displayName: user.name,
        roles: [user.role],
      },
    };
  }

  private hashPassword(password: string): string {
    const salt = randomBytes(16);
    const hash = scryptSync(password, salt, 64);
    return `scrypt:${salt.toString('hex')}:${hash.toString('hex')}`;
  }

  private verifyPassword(password: string, storedValue: string): boolean {
    const [algorithm, saltHex, hashHex] = storedValue.split(':');
    if (algorithm !== 'scrypt' || !saltHex || !hashHex) {
      return false;
    }

    try {
      const expectedHash = Buffer.from(hashHex, 'hex');
      const actualHash = scryptSync(password, Buffer.from(saltHex, 'hex'), expectedHash.length);
      return expectedHash.length > 0 && timingSafeEqual(actualHash, expectedHash);
    } catch {
      return false;
    }
  }
}
