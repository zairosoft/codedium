import { Module } from '@nestjs/common';
import { APP_GUARD } from '@nestjs/core';
import { ConfigService } from '@nestjs/config';
import { JwtModule } from '@nestjs/jwt';
import { PassportModule } from '@nestjs/passport';
import { TypeOrmModule } from '@nestjs/typeorm';
import { JwtAuthGuard } from '../workless/jwt/jwt-auth.guard';
import { resolveJwtSecret } from '../config/jwt.config';
import { JwtStrategy } from '../workless/jwt/jwt.strategy';
import { AuthController } from './controllers/auth.controller';
import { HomeController } from './controllers/home.controller';
import { LanguageController } from './controllers/language.controller';
import { UsersController } from './controllers/users.controller';
import { PlatformMembershipEntity } from './entities/membership.entity';
import { PlatformUserEntity } from './entities/user.entity';
import { NotificationsListener } from './providers/notifications.listener';
import { PermissionGuard } from './providers/permission.guard';
import { PermissionsService } from './providers/permissions.service';
import { UsersEventsListener } from './providers/users-events.listener';
import { UsersPolicy } from './providers/users.policy';
import {
  platformServiceExports,
  platformServiceProviders,
} from './providers/interfaces.service';

@Module({
  imports: [
    TypeOrmModule.forFeature([PlatformUserEntity, PlatformMembershipEntity]),
    PassportModule.register({ defaultStrategy: 'jwt' }),
    JwtModule.registerAsync({
      inject: [ConfigService],
      useFactory: (configService: ConfigService) => ({
        secret: resolveJwtSecret(configService),
        signOptions: {
          expiresIn: configService.get<string>('JWT_EXPIRES_IN', '1h') as any,
        },
      }),
    }),
  ],
  controllers: [AuthController, HomeController, LanguageController, UsersController],
  providers: [
    JwtStrategy,
    {
      provide: APP_GUARD,
      useClass: JwtAuthGuard,
    },
    NotificationsListener,
    PermissionGuard,
    UsersPolicy,
    UsersEventsListener,
    {
      provide: APP_GUARD,
      useClass: PermissionGuard,
    },
    ...platformServiceProviders,
  ],
  exports: platformServiceExports,
})
export class PlatformModule {}
