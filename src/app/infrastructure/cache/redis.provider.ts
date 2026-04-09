import { ConfigService } from '@nestjs/config';
import Redis from 'ioredis';

export function createRedisClient(configService: ConfigService): Redis | null {
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
}

