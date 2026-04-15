import { MiddlewareConsumer, Module, NestModule, RequestMethod } from '@nestjs/common';
import { APP_GUARD } from '@nestjs/core';
import { ConfigModule } from '@nestjs/config';
import { EventEmitterModule } from '@nestjs/event-emitter';
import { ThrottlerGuard, ThrottlerModule } from '@nestjs/throttler';
import { PlatformModule } from './app/platform.module';
import { CoreModule } from './core/core.module';
import { CacheModule } from './core/infrastructure/cache/cache.module';
import { DatabaseModule } from './database/database.module';
import { TenantContextMiddleware } from './core/tenant/tenant-context.middleware';
import { TenantModule } from './core/tenant/tenant.module';
import { loadRuntimeModules } from './modules/runtime-modules';

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
    TenantModule,
    DatabaseModule,
    CacheModule,
    PlatformModule,
    CoreModule,
    ...runtimeModules,
  ],
  providers: [
    {
      provide: APP_GUARD,
      useClass: ThrottlerGuard,
    },
  ],
})
export class AppModule implements NestModule {
  configure(consumer: MiddlewareConsumer) {
    consumer
      .apply(TenantContextMiddleware)
      .forRoutes({ path: '*', method: RequestMethod.ALL });
  }
}
