import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { AUTH_SERVICE } from '../core/interfaces/auth.interface';
import { NOTIFICATION_SERVICE } from '../core/interfaces/notification.interface';
import { PERMISSION_SERVICE } from '../core/interfaces/permission.interface';
import { ROLE_SERVICE } from '../core/interfaces/role.interface';
import { USER_SERVICE } from '../core/interfaces/user.interface';
import { LandingController } from './controllers/landing.controller';
import { UsersController } from './controllers/users.controller';
import { PlatformMembershipEntity } from './entities/platform-membership.entity';
import { PlatformUserEntity } from './entities/platform-user.entity';
import { NotificationsListener } from './providers/notifications.listener';
import { PermissionGuard } from './providers/permission.guard';
import { PermissionsService } from './providers/permissions.service';
import { UsersEventsListener } from './providers/users-events.listener';
import { UsersPolicy } from './providers/users.policy';
import { AuthService } from './services/auth.service';
import { NotificationsService } from './services/notifications.service';
import { RolesService } from './services/roles.service';
import { UsersService } from './services/users.service';

@Module({
  imports: [TypeOrmModule.forFeature([PlatformUserEntity, PlatformMembershipEntity])],
  controllers: [LandingController, UsersController],
  providers: [
    AuthService,
    NotificationsService,
    NotificationsListener,
    PermissionsService,
    PermissionGuard,
    RolesService,
    UsersService,
    UsersPolicy,
    UsersEventsListener,
    {
      provide: AUTH_SERVICE,
      useExisting: AuthService,
    },
    {
      provide: NOTIFICATION_SERVICE,
      useExisting: NotificationsService,
    },
    {
      provide: PERMISSION_SERVICE,
      useExisting: PermissionsService,
    },
    {
      provide: ROLE_SERVICE,
      useExisting: RolesService,
    },
    {
      provide: USER_SERVICE,
      useExisting: UsersService,
    },
  ],
  exports: [
    AuthService,
    NotificationsService,
    PermissionsService,
    RolesService,
    UsersService,
    AUTH_SERVICE,
    NOTIFICATION_SERVICE,
    PERMISSION_SERVICE,
    ROLE_SERVICE,
    USER_SERVICE,
  ],
})
export class PlatformModule {}
