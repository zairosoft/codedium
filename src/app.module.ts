import { MiddlewareConsumer, Module, NestModule, RequestMethod } from '@nestjs/common';
import { ConfigModule } from '@nestjs/config';
import { EventEmitterModule } from '@nestjs/event-emitter';
import { PlatformModule } from './app/platform.module';
import { TenantContextMiddleware } from './app/common/tenant/tenant-context.middleware';
import { TenantModule } from './app/common/tenant/tenant.module';
import { CacheModule } from './app/infrastructure/cache/cache.module';
import { DatabaseModule } from './app/infrastructure/database/database.module';
import { CoreModule } from './core/core.module';
import { AppsModule } from './modules/apps/module';
import { CrmModule } from './modules/crm/module';
import { HelpdeskModule } from './modules/helpdesk/module';
import { OrgModule } from './modules/org/module';

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
    OrgModule,
    CrmModule,
    HelpdeskModule,
    AppsModule,
  ],
})
export class AppModule implements NestModule {
  configure(consumer: MiddlewareConsumer) {
    consumer
      .apply(TenantContextMiddleware)
      .forRoutes({ path: '*', method: RequestMethod.ALL });
  }
}

