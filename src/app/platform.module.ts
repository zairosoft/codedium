import { Module } from '@nestjs/common';
import { AuthPlatformModule } from './auth/auth.module';
import { NotificationsPlatformModule } from './notifications/notifications.module';
import { PermissionsPlatformModule } from './permissions/permissions.module';
import { RolesPlatformModule } from './roles/roles.module';
import { UsersPlatformModule } from './users/users.module';

@Module({
  imports: [
    AuthPlatformModule,
    UsersPlatformModule,
    RolesPlatformModule,
    PermissionsPlatformModule,
    NotificationsPlatformModule,
  ],
  exports: [
    AuthPlatformModule,
    UsersPlatformModule,
    RolesPlatformModule,
    PermissionsPlatformModule,
    NotificationsPlatformModule,
  ],
})
export class PlatformModule {}
