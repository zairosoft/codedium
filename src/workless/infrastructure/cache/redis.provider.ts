import { Logger } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import Redis from 'ioredis';

export function createRedisClient(configService: ConfigService): Redis | null {
  const redisEnabled = configService.get<string>('REDIS_ENABLED', 'false') === 'true';
  if (!redisEnabled) {
    return null;
  }

  const redis = new Redis({
    host: configService.get<string>('REDIS_HOST', 'localhost'),
    port: Number(configService.get<number>('REDIS_PORT', 6379)),
    password: configService.get<string>('REDIS_PASSWORD', undefined),
    db: Number(configService.get<number>('REDIS_DB', 0)),
    connectTimeout: 3000,
    maxRetriesPerRequest: 1,
    retryStrategy: (attempt) => Math.min(attempt * 200, 2000),
  });

  const logger = new Logger('RedisDataCache');
  let lastError = '';
  redis.on('error', (error) => {
    if (error.message !== lastError) {
      lastError = error.message;
      logger.warn(`Redis connection error: ${error.message}`);
    }
  });
  redis.on('ready', () => {
    lastError = '';
  });

  return redis;
}
