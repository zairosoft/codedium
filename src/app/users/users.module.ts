import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { USER_SERVICE } from '../../core/interfaces/user.interface';
import { PermissionsPlatformModule } from '../permissions/permissions.module';
import { UsersController } from './controllers/users.controller';
import { PlatformMembershipEntity } from './entities/platform-membership.entity';
import { PlatformUserEntity } from './entities/platform-user.entity';
import { UsersPolicy } from './policies/users.policy';
import { UsersEventsListener } from './services/users-events.listener';
import { UsersService } from './services/users.service';

@Module({
  imports: [TypeOrmModule.forFeature([PlatformUserEntity, PlatformMembershipEntity]), PermissionsPlatformModule],
  controllers: [UsersController],
  providers: [
    UsersService,
    UsersPolicy,
    UsersEventsListener,
    {
      provide: USER_SERVICE,
      useExisting: UsersService,
    },
  ],
  exports: [UsersService, USER_SERVICE],
})
export class UsersPlatformModule {}
