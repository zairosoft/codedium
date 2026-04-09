import { Inject, Injectable } from '@nestjs/common';
import { REDIS_CLIENT } from './cache.constants';
import { CacheStore, InMemoryCacheStore, RedisCacheStore } from './cache.store';
import Redis from 'ioredis';

@Injectable()
export class CacheService {
  private readonly store: CacheStore;

  constructor(@Inject(REDIS_CLIENT) redisClient: Redis | null) {
    this.store = redisClient ? new RedisCacheStore(redisClient) : new InMemoryCacheStore();
  }

  get<T>(key: string): Promise<T | null> {
    return this.store.get<T>(key);
  }

  set<T>(key: string, value: T, ttlSeconds?: number): Promise<void> {
    return this.store.set(key, value, ttlSeconds);
  }

  del(key: string): Promise<void> {
    return this.store.del(key);
  }

  async remember<T>(key: string, ttlSeconds: number, resolver: () => Promise<T>): Promise<T> {
    const cached = await this.get<T>(key);
    if (cached !== null) {
      return cached;
    }

    const value = await resolver();
    await this.set(key, value, ttlSeconds);
    return value;
  }
}
