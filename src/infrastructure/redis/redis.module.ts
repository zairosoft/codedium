import { Global, Module, OnModuleDestroy, Inject } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import Redis from 'ioredis';
import { CACHE_STORE, QUEUE_ADAPTER, REDIS_CLIENT, SESSION_STORE } from './redis.constants';
import {
  CacheStore,
  InMemoryCacheStore,
  RedisCacheStore,
} from './cache.store';
import {
  InMemorySessionStore,
  RedisSessionStore,
  SessionStore,
} from './session.store';
import {
  InMemoryQueueAdapter,
  QueueAdapter,
  QueueConnection,
  RedisQueueAdapter,
} from './queue.adapter';

@Global()
@Module({
  providers: [
    {
      provide: REDIS_CLIENT,
      inject: [ConfigService],
      useFactory: (configService: ConfigService): Redis | null => {
        const redisEnabled = configService.get<string>('REDIS_ENABLED', 'false') === 'true';
        if (!redisEnabled) {
          return null;
        }

        return new Redis({
          host: configService.get<string>('REDIS_HOST', 'localhost'),
          port: Number(configService.get<number>('REDIS_PORT', 6379)),
          password: configService.get<string>('REDIS_PASSWORD', undefined),
          db: Number(configService.get<number>('REDIS_DB', 0)),
          maxRetriesPerRequest: 1,
        });
      },
    },
    {
      provide: CACHE_STORE,
      inject: [REDIS_CLIENT],
      useFactory: (redisClient: Redis | null): CacheStore =>
        redisClient ? new RedisCacheStore(redisClient) : new InMemoryCacheStore(),
    },
    {
      provide: SESSION_STORE,
      inject: [REDIS_CLIENT],
      useFactory: (redisClient: Redis | null): SessionStore =>
        redisClient
          ? new RedisSessionStore(redisClient)
          : new InMemorySessionStore(),
    },
    {
      provide: QUEUE_ADAPTER,
      inject: [ConfigService],
      useFactory: (configService: ConfigService): QueueAdapter => {
        const redisEnabled = configService.get<string>('REDIS_ENABLED', 'false') === 'true';
        if (!redisEnabled) {
          return new InMemoryQueueAdapter();
        }

        const connection: QueueConnection = {
          host: configService.get<string>('REDIS_HOST', 'localhost'),
          port: Number(configService.get<number>('REDIS_PORT', 6379)),
          password: configService.get<string>('REDIS_PASSWORD', undefined),
          db: Number(configService.get<number>('REDIS_DB', 0)),
        };

        return new RedisQueueAdapter(connection);
      },
    },
  ],
  exports: [REDIS_CLIENT, CACHE_STORE, SESSION_STORE, QUEUE_ADAPTER],
})
export class RedisModule implements OnModuleDestroy {
  constructor(
    @Inject(REDIS_CLIENT) private readonly redisClient: Redis | null,
    @Inject(QUEUE_ADAPTER) private readonly queueAdapter: QueueAdapter,
  ) {}

  async onModuleDestroy(): Promise<void> {
    if (this.queueAdapter.close) {
      await this.queueAdapter.close();
    }

    if (this.redisClient) {
      await this.redisClient.quit();
    }
  }
}

