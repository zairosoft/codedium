import { MiddlewareConsumer, Module, NestModule, RequestMethod } from '@nestjs/common';
import { ConfigModule } from '@nestjs/config';
import { EventEmitterModule } from '@nestjs/event-emitter';
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
    TenantModule,
    DatabaseModule,
    CacheModule,
    PlatformModule,
    CoreModule,
    ...runtimeModules,
  ],
})
export class AppModule implements NestModule {
  configure(consumer: MiddlewareConsumer) {
    consumer
      .apply(TenantContextMiddleware)
      .forRoutes({ path: '*', method: RequestMethod.ALL });
  }
}
