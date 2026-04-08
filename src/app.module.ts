import { MiddlewareConsumer, Module, NestModule, RequestMethod } from '@nestjs/common';
import { ConfigModule } from '@nestjs/config';
import { EventEmitterModule } from '@nestjs/event-emitter';
import { TenantContextMiddleware } from './common/tenant/tenant-context.middleware';
import { TenantModule } from './common/tenant/tenant.module';
import { CoreModule } from './core/core.module';
import { DatabaseModule } from './infrastructure/database/database.module';
import { CacheModule } from './infrastructure/cache/cache.module';
import { AppsModule } from './modules/apps/module';
import { AuthModule } from './modules/auth/module';
import { CrmModule } from './modules/crm/module';
import { HelpdeskModule } from './modules/helpdesk/module';
import { NotificationsModule } from './modules/notifications/module';
import { OrgModule } from './modules/org/module';
import { PermissionsModule } from './modules/permissions/module';
import { UsersModule } from './modules/users/module';

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
    CoreModule,
    AuthModule,
    UsersModule,
    OrgModule,
    CrmModule,
    HelpdeskModule,
    PermissionsModule,
    AppsModule,
    NotificationsModule,
  ],
})
export class AppModule implements NestModule {
  configure(consumer: MiddlewareConsumer) {
    consumer
      .apply(TenantContextMiddleware)
      .forRoutes({ path: '*', method: RequestMethod.ALL });
  }
}

