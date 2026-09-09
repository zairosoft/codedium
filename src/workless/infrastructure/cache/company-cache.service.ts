import { Inject, Injectable } from '@nestjs/common';
import { createHash } from 'node:crypto';
import { isUUID } from 'class-validator';
import { COMPANY_CONTEXT, CompanyContextPort } from '@/app/interfaces/company-context.interface';
import { CacheService } from '@/workless/infrastructure/cache/cache.service';

export type CacheQuery =
  | null
  | boolean
  | number
  | string
  | CacheQuery[]
  | { [key: string]: CacheQuery };

/** JSON-only, stable encoding. Reject ambiguous inputs instead of silently sharing keys. */
function canonical(value: CacheQuery, ancestors = new Set<object>()): string {
  if (value === null || typeof value === 'string' || typeof value === 'boolean') {
    return JSON.stringify(value);
  }
  if (typeof value === 'number' && Number.isFinite(value)) return JSON.stringify(value);
  if (typeof value !== 'object' || !value) throw new TypeError('Cache query must contain only JSON values.');
  if (ancestors.has(value)) throw new TypeError('Cache query cannot be circular.');
  ancestors.add(value);
  try {
    if (Array.isArray(value)) {
      return '[' + Array.from(value, (item) => canonical(item, ancestors)).join(',') + ']';
    }
    if (Object.getPrototypeOf(value) !== Object.prototype && Object.getPrototypeOf(value) !== null) {
      throw new TypeError('Cache query objects must be plain objects.');
    }
    return (
      '{' +
      Object.keys(value)
        .sort()
        .map((key) => JSON.stringify(key) + ':' + canonical(value[key], ancestors))
        .join(',') +
      '}'
    );
  } finally {
    ancestors.delete(value);
  }
}

@Injectable()
export class CompanyCacheService {
  constructor(
    @Inject(COMPANY_CONTEXT) private readonly context: CompanyContextPort,
    @Inject(CacheService) private readonly cache: CacheService,
  ) {}

  async get<T>(table: string, query: CacheQuery, dependencies: string[] = []): Promise<T | null> {
    return this.cache.get<T>(await this.key(table, query, dependencies), true);
  }

  async set<T>(
    table: string,
    query: CacheQuery,
    value: T,
    ttlSeconds = 300,
    dependencies: string[] = [],
  ): Promise<void> {
    await this.cache.set(await this.key(table, query, dependencies), value, ttlSeconds, true);
  }

  async remember<T>(
    table: string,
    query: CacheQuery,
    ttlSeconds: number,
    resolver: () => Promise<T>,
    dependencies: string[] = [],
  ): Promise<T> {
    // Capture the generation BEFORE resolving; concurrent invalidation strands stale writes in the old generation.
    return this.cache.remember(await this.key(table, query, dependencies), ttlSeconds, resolver, true);
  }

  async del(table: string, query: CacheQuery, dependencies: string[] = []): Promise<void> {
    await this.cache.del(await this.key(table, query, dependencies), true);
  }

  async invalidateTable(table: string): Promise<void> {
    await this.invalidateTables(table);
  }

  async invalidateTables(...tables: string[]): Promise<void> {
    const companyId = this.companyId();
    const namespaces = [...new Set(tables)].map((table) => this.namespace(companyId, table));
    if (namespaces.length === 0) throw new Error('At least one cache table is required.');
    await Promise.all(namespaces.map((namespace) => this.cache.namespaceVersion(namespace, true)));
  }

  private companyId(): string {
    const id = this.context.requireCompanyId().trim().toLowerCase();
    if (!isUUID(id)) throw new Error('A valid company id is required for company cache.');
    return id;
  }

  private namespace(companyId: string, table: string): string {
    if (!/^[a-z][a-z0-9_]{0,62}$/.test(table)) {
      throw new Error('Cache table must be a lowercase identifier (up to 63 characters).');
    }
    return `company:${companyId}:table:${table}`;
  }

  private async key(table: string, query: CacheQuery, dependencies: string[]): Promise<string> {
    const companyId = this.companyId();
    const namespace = this.namespace(companyId, table);
    const digest = createHash('sha256').update(canonical(query)).digest('hex');
    const tables = [...new Set([table, ...dependencies])].sort();
    const namespaces = tables.map((name) => this.namespace(companyId, name));
    const versions = await Promise.all(namespaces.map((name) => this.cache.namespaceVersion(name)));
    const generation = createHash('sha256').update(JSON.stringify([tables, versions])).digest('hex');
    return `${namespace}:v:${generation}:${digest}`;
  }
}
