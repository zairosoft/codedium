import Redis from 'ioredis';

export interface CacheStore {
  get<T>(key: string): Promise<T | null>;
  set<T>(key: string, value: T, ttlSeconds?: number): Promise<void>;
  del(key: string): Promise<void>;
  delByPrefix(prefix: string): Promise<void>;
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

    try {
      return JSON.parse(entry.value) as T;
    } catch {
      this.data.delete(key);
      return null;
    }
  }

  async set<T>(key: string, value: T, ttlSeconds?: number): Promise<void> {
    const expiresAt = ttlSeconds ? Date.now() + ttlSeconds * 1000 : undefined;
    const serializedValue = JSON.stringify(value);
    if (serializedValue === undefined) {
      throw new TypeError('Cache value must be JSON serializable.');
    }
    this.data.set(key, {
      value: serializedValue,
      expiresAt,
    });
  }

  async del(key: string): Promise<void> {
    this.data.delete(key);
  }

  async delByPrefix(prefix: string): Promise<void> {
    for (const key of this.data.keys()) {
      if (key.startsWith(prefix)) {
        this.data.delete(key);
      }
    }
  }
}

export class RedisCacheStore implements CacheStore {
  constructor(private readonly redis: Redis) {}

  async get<T>(key: string): Promise<T | null> {
    const raw = await this.redis.get(key);
    if (!raw) {
      return null;
    }

    try {
      return JSON.parse(raw) as T;
    } catch {
      await this.redis.del(key);
      return null;
    }
  }

  async set<T>(key: string, value: T, ttlSeconds?: number): Promise<void> {
    const serializedValue = JSON.stringify(value);
    if (serializedValue === undefined) {
      throw new TypeError('Cache value must be JSON serializable.');
    }

    if (ttlSeconds !== undefined) {
      await this.redis.set(key, serializedValue, 'EX', ttlSeconds);
      return;
    }

    await this.redis.set(key, serializedValue);
  }

  async del(key: string): Promise<void> {
    await this.redis.del(key);
  }

  async delByPrefix(prefix: string): Promise<void> {
    const pattern = `${prefix.replace(/[\\*?[\]]/g, '\\$&')}*`;
    let cursor = '0';

    do {
      const [nextCursor, keys] = await this.redis.scan(
        cursor,
        'MATCH',
        pattern,
        'COUNT',
        100,
      );
      cursor = nextCursor;

      if (keys.length > 0) {
        await this.redis.unlink(...keys);
      }
    } while (cursor !== '0');
  }
}
