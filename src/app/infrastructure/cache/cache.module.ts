import { Global, Inject, Module, OnModuleDestroy } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import Redis from 'ioredis';
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
  ],
  exports: [REDIS_CLIENT, CacheService],
})
export class CacheModule implements OnModuleDestroy {
  constructor(@Inject(REDIS_CLIENT) private readonly redisClient: Redis | null) {}

  async onModuleDestroy(): Promise<void> {
    if (this.redisClient) {
      await this.redisClient.quit();
    }
  }
}

