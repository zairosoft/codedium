import { randomUUID } from 'node:crypto';
import { Inject, Injectable, Logger } from '@nestjs/common';
import Redis from 'ioredis';
import { REDIS_CLIENT } from '@/workless/infrastructure/cache/cache.constants';
import { CachePort } from '@/workless/infrastructure/cache/cache.interface';
import { CacheStore, InMemoryCacheStore, RedisCacheStore } from '@/workless/infrastructure/cache/cache.store';

@Injectable()
export class CacheService implements CachePort {
  private static readonly KEY_PREFIX = 'workless:data:';
  private static readonly DEFAULT_TTL_SECONDS = 300;
  private readonly logger = new Logger(CacheService.name);
  private readonly store: CacheStore;
  private readonly fallbackStore = new InMemoryCacheStore();
  private readonly reportedFailures = new Set<string>();
  private readonly pendingResolvers = new Map<string, Promise<unknown>>();
  private readonly versions = new Map<string, string>();

  constructor(@Inject(REDIS_CLIENT) private readonly redisClient: Redis | null) {
    this.store = redisClient ? new RedisCacheStore(redisClient) : new InMemoryCacheStore();
  }

  async get<T>(key: string, strict = false): Promise<T | null> {
    const normalizedKey = this.normalizeKey(key);
    try {
      return await this.store.get<T>(normalizedKey);
    } catch (error) {
      if (strict) throw error;
      this.reportFailure('get', error);
      return this.fallbackStore.get<T>(normalizedKey);
    }
  }

  async set<T>(
    key: string,
    value: T,
    ttlSeconds?: number,
    strict = false,
  ): Promise<void> {
    const normalizedKey = this.normalizeKey(key);
    const ttl = this.normalizeTtl(ttlSeconds);
    this.assertCacheable(value);
    try {
      await this.store.set(normalizedKey, value, ttl);
    } catch (error) {
      if (strict) throw error;
      this.reportFailure('set', error);
      await this.fallbackStore.set(normalizedKey, value, ttl);
    }
  }

  async del(key: string, strict = false): Promise<void> {
    const normalizedKey = this.normalizeKey(key);
    try {
      await this.store.del(normalizedKey);
    } catch (error) {
      if (strict) throw error;
      this.reportFailure('del', error);
    }
    await this.fallbackStore.del(normalizedKey);
  }

  async delByPrefix(prefix: string): Promise<void> {
    const normalizedPrefix = this.normalizeKey(prefix);
    try {
      await this.store.delByPrefix(normalizedPrefix);
    } catch (error) {
      this.reportFailure('delByPrefix', error);
    }
    await this.fallbackStore.delByPrefix(normalizedPrefix);
  }

  async remember<T>(
    key: string,
    ttlSeconds: number,
    resolver: () => Promise<T>,
    strict = false,
  ): Promise<T> {
    this.normalizeTtl(ttlSeconds);
    const cached = await this.get<T>(key, strict);
    if (cached !== null) {
      return cached;
    }

    const normalizedKey = this.normalizeKey(key);
    const pending = this.pendingResolvers.get(normalizedKey) as Promise<T> | undefined;
    if (pending) {
      return pending;
    }

    const resolution = (async () => {
      const value = await resolver();
      await this.set(key, value, ttlSeconds, strict);
      return value;
    })();
    this.pendingResolvers.set(normalizedKey, resolution);

    try {
      return await resolution;
    } finally {
      this.pendingResolvers.delete(normalizedKey);
    }
  }

  /**
   * Persistent random generations prevent old entries resurfacing after metadata eviction.
   * Redis failures deliberately propagate: process-local fallback cannot coordinate invalidation.
   */
  async namespaceVersion(namespace: string, rotate = false): Promise<string> {
    const key = this.normalizeKey(`${namespace}:version`);
    const token = randomUUID();
    if (!this.redisClient) {
      if (rotate || !this.versions.has(key)) this.versions.set(key, token);
      return this.versions.get(key)!;
    }
    if (rotate) {
      await this.redisClient.set(key, token);
      return token;
    }
    await this.redisClient.set(key, token, 'NX');
    const current = await this.redisClient.get(key);
    if (!current) throw new Error('Cache namespace version is unavailable.');
    return current;
  }

  private normalizeKey(key: string): string {
    const normalized = key.trim().replace(/^:+/, '');
    if (!normalized) {
      throw new Error('Cache key cannot be empty.');
    }
    if (normalized.length > 500) {
      throw new Error('Cache key cannot exceed 500 characters.');
    }

    return normalized.startsWith(CacheService.KEY_PREFIX)
      ? normalized
      : `${CacheService.KEY_PREFIX}${normalized}`;
  }

  private normalizeTtl(ttlSeconds?: number): number {
    const ttl = ttlSeconds ?? CacheService.DEFAULT_TTL_SECONDS;
    if (!Number.isInteger(ttl) || ttl <= 0) {
      throw new Error('Cache TTL must be a positive integer in seconds.');
    }

    return ttl;
  }

  private assertCacheable(value: unknown): void {
    if (this.containsHtml(value)) {
      throw new TypeError('HTML documents cannot be stored in the data cache.');
    }

    try {
      if (JSON.stringify(value) === undefined) {
        throw new TypeError('Cache value must be JSON serializable.');
      }
    } catch (error) {
      if (error instanceof TypeError) {
        throw error;
      }
      throw new TypeError('Cache value must be JSON serializable.');
    }
  }

  private containsHtml(value: unknown, visited = new WeakSet<object>()): boolean {
    if (typeof value === 'string') {
      return /^(?:<!doctype\s+html|<\/?[a-z][^>]*>)/i.test(value.trimStart());
    }

    if (value === null || typeof value !== 'object') {
      return false;
    }

    if (visited.has(value)) {
      return false;
    }
    visited.add(value);

    return Object.values(value).some((item) => this.containsHtml(item, visited));
  }

  private reportFailure(operation: string, error: unknown): void {
    if (this.reportedFailures.has(operation)) {
      return;
    }

    this.reportedFailures.add(operation);
    const message = error instanceof Error ? error.message : String(error);
    this.logger.warn(`Cache ${operation} failed; using in-memory fallback: ${message}`);
  }
}
