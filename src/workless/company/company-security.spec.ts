import { scryptSync } from 'node:crypto';
import { UnauthorizedException } from '@nestjs/common';
import type { ExecutionContext } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import { describe, expect, it, vi } from 'vitest';
import { AuthService } from '@/app/services/auth.service';
import { CompanyContextGuard } from '@/workless/company/company-context.guard';
import { CompanyContextService } from '@/workless/company/company-context.service';
import { DEFAULT_COMPANY_ID } from '@/workless/company/company.constants';
import { JwtStrategy } from '@/app/providers/jwt.strategy';

const COMPANY_A = '11111111-1111-4111-8111-111111111111';
const COMPANY_B = '22222222-2222-4222-8222-222222222222';
const USER_ID = '33333333-3333-4333-8333-333333333333';

function httpContext(request: object): ExecutionContext {
  return {
    getType: () => 'http',
    switchToHttp: () => ({ getRequest: () => request }),
  } as unknown as ExecutionContext;
}

function userFixture() {
  const salt = Buffer.from('0123456789abcdef', 'utf8');
  const hash = scryptSync('correct-password', salt, 32);
  return {
    id: USER_ID,
    companyId: COMPANY_A,
    name: 'Company User',
    email: 'user@example.com',
    password: `scrypt:${salt.toString('hex')}:${hash.toString('hex')}`,
    role: 'user',
    isActive: true,
  };
}

describe('company data boundary', () => {
  it('activates companyId from the authenticated database user, not request headers', () => {
    const companyContext = new CompanyContextService();
    const guard = new CompanyContextGuard(companyContext);
    const context = httpContext({
      headers: { 'x-tenant-id': COMPANY_B, 'x-company-id': COMPANY_B },
      user: { companyId: COMPANY_A },
    });

    companyContext.run(DEFAULT_COMPANY_ID, () => {
      expect(guard.canActivate(context)).toBe(true);
      expect(companyContext.getCompanyId()).toBe(COMPANY_A);
    });
  });

  it('issues JWT companyId only from the user record', async () => {
    const user = userFixture();
    const queryBuilder = {
      addSelect: vi.fn().mockReturnThis(),
      where: vi.fn().mockReturnThis(),
      getOne: vi.fn().mockResolvedValue(user),
    };
    const repository = {
      createQueryBuilder: vi.fn().mockReturnValue(queryBuilder),
      update: vi.fn().mockResolvedValue(undefined),
    };
    const jwtService = { sign: vi.fn().mockReturnValue('signed-token') };
    const authService = new AuthService(repository as never, jwtService as never);

    await expect(authService.login(user.email, 'correct-password')).resolves.toMatchObject({
      accessToken: 'signed-token',
    });
    expect(jwtService.sign).toHaveBeenCalledWith(expect.objectContaining({ companyId: COMPANY_A }));
  });

  it('rejects a token when its companyId differs from the database user', async () => {
    const repository = { findOne: vi.fn().mockResolvedValue(userFixture()) };
    const configService = { get: vi.fn().mockReturnValue('test-jwt-secret') } as unknown as ConfigService;
    const strategy = new JwtStrategy(configService, repository as never);

    await expect(
      strategy.validate({
        userId: USER_ID,
        email: 'user@example.com',
        companyId: COMPANY_B,
        roles: ['user'],
      }),
    ).rejects.toBeInstanceOf(UnauthorizedException);
  });
});
