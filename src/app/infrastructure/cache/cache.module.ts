import { Global, Inject, Module, OnModuleDestroy } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import Redis from 'ioredis';
import { CACHE_PORT } from '../../interfaces/cache.interface';
import { REDIS_CLIENT } from './cache.constants';
import { CacheService } from './cache.service';
import { createRedisClient } from './redis.provider';

@Global()
@Module({
  providers: [
    {
      provide: REDIS_CLIENT,
      inject: [ConfigService],
      useFactory: createRedisClient,
    },
    CacheService,
    {
      provide: CACHE_PORT,
      useExisting: CacheService,
    },
  ],
  exports: [REDIS_CLIENT, CacheService, CACHE_PORT],
})
export class CacheModule implements OnModuleDestroy {
  constructor(@Inject(REDIS_CLIENT) private readonly redisClient: Redis | null) {}

  async onModuleDestroy(): Promise<void> {
    if (this.redisClient) {
      await this.redisClient.quit();
    }
  }
}

