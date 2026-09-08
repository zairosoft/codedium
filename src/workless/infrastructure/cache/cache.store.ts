import Redis from 'ioredis';

export interface CacheStore {
  get<T>(key: string): Promise<T | null>;
  set<T>(key: string, value: T, ttlSeconds?: number): Promise<void>;
  del(key: string): Promise<void>;
}

type MemoryValue = {
  value: string;
  expiresAt?: number;
};

export class InMemoryCacheStore implements CacheStore {
  private readonly data = new Map<string, MemoryValue>();

  async get<T>(key: string): Promise<T | null> {
    const entry = this.data.get(key);
    if (!entry) {
      return null;
    }

    if (entry.expiresAt && entry.expiresAt < Date.now()) {
      this.data.delete(key);
      return null;
    }

    return JSON.parse(entry.value) as T;
  }

  async set<T>(key: string, value: T, ttlSeconds?: number): Promise<void> {
    const expiresAt = ttlSeconds ? Date.now() + ttlSeconds * 1000 : undefined;
    this.data.set(key, {
      value: JSON.stringify(value),
      expiresAt,
    });
  }

  async del(key: string): Promise<void> {
    this.data.delete(key);
  }
}

export class RedisCacheStore implements CacheStore {
  constructor(private readonly redis: Redis) {}

  async get<T>(key: string): Promise<T | null> {
    const raw = await this.redis.get(key);
    return raw ? (JSON.parse(raw) as T) : null;
  }

  async set<T>(key: string, value: T, ttlSeconds?: number): Promise<void> {
    const serializedValue = JSON.stringify(value);

    if (ttlSeconds !== undefined) {
      await this.redis.set(key, serializedValue, 'EX', ttlSeconds);
      return;
    }

    await this.redis.set(key, serializedValue);
  }

  async del(key: string): Promise<void> {
    await this.redis.del(key);
  }
}
