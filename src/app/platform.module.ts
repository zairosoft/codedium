import { Module } from '@nestjs/common';
import { APP_GUARD } from '@nestjs/core';
import { ConfigService } from '@nestjs/config';
import { JwtModule } from '@nestjs/jwt';
import { PassportModule } from '@nestjs/passport';
import { TypeOrmModule } from '@nestjs/typeorm';
import { AUTH_SERVICE } from '../workless/interfaces/auth.interface';
import { NOTIFICATION_SERVICE } from '../workless/interfaces/notification.interface';
import { PERMISSION_SERVICE } from '../workless/interfaces/permission.interface';
import { ROLE_SERVICE } from '../workless/interfaces/role.interface';
import { USER_SERVICE } from '../workless/interfaces/user.interface';
import { JwtAuthGuard } from './auth/jwt-auth.guard';
import { JwtStrategy } from './auth/jwt.strategy';
import { AuthController } from './controllers/auth.controller';
import { HomeController } from './controllers/home.controller';
import { LanguageController } from './controllers/language.controller';
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
  imports: [
    TypeOrmModule.forFeature([PlatformUserEntity, PlatformMembershipEntity]),
    PassportModule.register({ defaultStrategy: 'jwt' }),
    JwtModule.registerAsync({
      inject: [ConfigService],
      useFactory: (configService: ConfigService) => ({
        secret: configService.get<string>('JWT_SECRET', 'dev-secret-change-me'),
        signOptions: {
          expiresIn: configService.get<string>('JWT_EXPIRES_IN', '1h') as any,
        },
      }),
    }),
  ],
  controllers: [AuthController, HomeController, LanguageController, UsersController],
  providers: [
    AuthService,
    JwtStrategy,
    {
      provide: APP_GUARD,
      useClass: JwtAuthGuard,
    },
    NotificationsService,
    NotificationsListener,
    PermissionsService,
    PermissionGuard,
    RolesService,
    UsersService,
    UsersPolicy,
    UsersEventsListener,
    {
      provide: APP_GUARD,
      useClass: PermissionGuard,
    },
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
