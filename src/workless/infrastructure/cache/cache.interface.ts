/**
 * Generic JSON data cache. HTML documents are intentionally rejected.
 *
 * Use CompanyCacheService for company-owned records. Direct CachePort access is
 * reserved for shared system data whose namespace is managed by the caller.
 * CacheService adds the physical `workless:data:` prefix.
 */
export interface CachePort {
  get<T>(key: string): Promise<T | null>;
  set<T>(key: string, value: T, ttlSeconds?: number): Promise<void>;
  del(key: string): Promise<void>;
  delByPrefix(prefix: string): Promise<void>;
  remember<T>(key: string, ttlSeconds: number, resolver: () => Promise<T>): Promise<T>;
}

export const CACHE_PORT = Symbol('CACHE_PORT');
