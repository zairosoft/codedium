/**
 * Generic JSON data cache. HTML documents are intentionally rejected.
 *
 * Callers own their logical namespaces. Use the convention
 * `<area>:<resource>:<operation-or-id>[:<scope>...]` and include a company or
 * other ownership id whenever data is scoped. CacheService adds the physical
 * `workless:data:` prefix; consumers must not add it themselves.
 */
export interface CachePort {
  get<T>(key: string): Promise<T | null>;
  set<T>(key: string, value: T, ttlSeconds?: number): Promise<void>;
  del(key: string): Promise<void>;
  delByPrefix(prefix: string): Promise<void>;
  remember<T>(key: string, ttlSeconds: number, resolver: () => Promise<T>): Promise<T>;
}

export const CACHE_PORT = Symbol('CACHE_PORT');
