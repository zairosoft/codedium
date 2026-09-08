import { Module } from '@nestjs/common';
import { APP_GUARD } from '@nestjs/core';
import { ConfigModule } from '@nestjs/config';
import { EventEmitterModule } from '@nestjs/event-emitter';
import { ThrottlerGuard, ThrottlerModule } from '@nestjs/throttler';
import { PlatformModule } from '@app/app';
import { loadRuntimeModules } from '@modules/modules';
import { WorklessModule } from '@/workless/workless.module';
import { CacheModule } from '@/workless/infrastructure/cache/cache.module';
import { DatabaseModule } from '@/database/database.module';

const runtimeModules = loadRuntimeModules();

@Module({
  imports: [
    ConfigModule.forRoot({
      isGlobal: true,
      envFilePath: ['.env.local', '.env'],
    }),
    EventEmitterModule.forRoot(),
    ThrottlerModule.forRoot([
      {
        name: 'short',
        ttl: 1000,
        limit: 10,
      },
      {
        name: 'medium',
        ttl: 10000,
        limit: 50,
      },
      {
        name: 'long',
        ttl: 60000,
        limit: 200,
      },
    ]),
    DatabaseModule,
    CacheModule,
    PlatformModule,
    WorklessModule,
    ...runtimeModules,
  ],
  providers: [
    {
      provide: APP_GUARD,
      useClass: ThrottlerGuard,
    },
  ],
})
export class AppModule {}
