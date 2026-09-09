import { describe, expect, it, vi } from 'vitest';
import { CompanyContextPort } from '@/app/interfaces/company-context.interface';
import { CacheService } from '@/workless/infrastructure/cache/cache.service';
import { CompanyCacheService } from '@/workless/infrastructure/cache/company-cache.service';

const COMPANY_A = '700234f5-0e45-452e-ae3a-70b4b3d024e1';
const COMPANY_B = '8cb5b513-3392-46ef-a573-3cce9c12d489';

function createSubject() {
  let companyId: string | null = COMPANY_A;
  const context: CompanyContextPort = {
    getCompanyId: () => companyId,
    requireCompanyId: () => {
      if (!companyId) throw new Error('Company context is required.');
      return companyId;
    },
  };
  const cache = new CacheService(null);

  return {
    subject: new CompanyCacheService(context, cache),
    selectCompany: (id: string | null) => {
      companyId = id;
    },
  };
}

describe('CompanyCacheService', () => {
  it('isolates identical table queries between companies', async () => {
    const { subject, selectCompany } = createSubject();

    await subject.set('users', { page: 1 }, ['company-a-user'], 60);
    selectCompany(COMPANY_B);
    await subject.set('users', { page: 1 }, ['company-b-user'], 60);

    await expect(subject.get('users', { page: 1 })).resolves.toEqual(['company-b-user']);
    selectCompany(COMPANY_A);
    await expect(subject.get('users', { page: 1 })).resolves.toEqual(['company-a-user']);
  });

  it('isolates the same table and query across more than 100 companies', async () => {
    const { subject, selectCompany } = createSubject();
    const companyIds = Array.from({ length: 120 }, (_, index) => {
      const suffix = (index + 1).toString(16).padStart(12, '0');
      return `700234f5-0e45-452e-ae3a-${suffix}`;
    });

    for (const [index, companyId] of companyIds.entries()) {
      selectCompany(companyId);
      await subject.set('users', { page: 1 }, { companyNumber: index + 1 }, 60);
    }

    for (const [index, companyId] of companyIds.entries()) {
      selectCompany(companyId);
      await expect(subject.get('users', { page: 1 })).resolves.toEqual({
        companyNumber: index + 1,
      });
    }
  });

  it('uses the same key for query objects regardless of property order', async () => {
    const { subject } = createSubject();

    await subject.set('users', { page: 1, filters: { active: true, role: 'admin' } }, 'cached', 60);

    await expect(
      subject.get('users', { filters: { role: 'admin', active: true }, page: 1 }),
    ).resolves.toBe('cached');
  });

  it('invalidates one table only for the current company', async () => {
    const { subject, selectCompany } = createSubject();
    await subject.set('users', null, 'a-users', 60);
    await subject.set('orders', null, 'a-orders', 60);
    selectCompany(COMPANY_B);
    await subject.set('users', null, 'b-users', 60);

    selectCompany(COMPANY_A);
    await subject.invalidateTable('users');

    await expect(subject.get('users', null)).resolves.toBeNull();
    await expect(subject.get('orders', null)).resolves.toBe('a-orders');
    selectCompany(COMPANY_B);
    await expect(subject.get('users', null)).resolves.toBe('b-users');
  });

  it('invalidates a multi-table result when any dependency changes', async () => {
    const { subject } = createSubject();
    await subject.set('dashboard', { period: 'today' }, { total: 2 }, 60, ['users', 'orders']);

    await subject.invalidateTable('orders');

    await expect(
      subject.get('dashboard', { period: 'today' }, ['users', 'orders']),
    ).resolves.toBeNull();
  });

  it('invalidates multiple tables for the current company in one call', async () => {
    const { subject } = createSubject();
    await subject.set('users', null, 'users', 60);
    await subject.set('orders', null, 'orders', 60);

    await subject.invalidateTables('users', 'orders');

    await expect(subject.get('users', null)).resolves.toBeNull();
    await expect(subject.get('orders', null)).resolves.toBeNull();
  });

  it('does not revive a stale result when invalidation occurs during resolution', async () => {
    const { subject } = createSubject();
    let release!: () => void;
    const waiting = new Promise<void>((resolve) => {
      release = resolve;
    });
    const staleResolver = vi.fn(async () => {
      await waiting;
      return 'stale';
    });

    const staleRequest = subject.remember('users', null, 60, staleResolver);
    await subject.invalidateTable('users');
    release();
    await expect(staleRequest).resolves.toBe('stale');

    const freshResolver = vi.fn(async () => 'fresh');
    await expect(subject.remember('users', null, 60, freshResolver)).resolves.toBe('fresh');
    expect(freshResolver).toHaveBeenCalledOnce();
  });

  it('rejects missing company context and invalid table names', async () => {
    const { subject, selectCompany } = createSubject();
    selectCompany(null);
    await expect(subject.get('users', null)).rejects.toThrow('Company context is required.');

    selectCompany(COMPANY_A);
    await expect(subject.get('User Profiles', null)).rejects.toThrow(
      'Cache table must be a lowercase identifier',
    );
  });
});
