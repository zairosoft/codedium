export interface CachePort {
  get<T>(key: string): Promise<T | null>;
  set<T>(key: string, value: T, ttlSeconds?: number): Promise<void>;
  del(key: string): Promise<void>;
  remember<T>(key: string, ttlSeconds: number, resolver: () => Promise<T>): Promise<T>;
}

export const CACHE_PORT = Symbol('CACHE_PORT');
