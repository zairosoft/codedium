import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { USER_SERVICE } from '../interfaces/user.interface';
import { PlatformUserEntity } from './entities/platform-user.entity';
import { UsersService } from './services/users.service';

@Module({
  imports: [TypeOrmModule.forFeature([PlatformUserEntity])],
  providers: [
    UsersService,
    {
      provide: USER_SERVICE,
      useExisting: UsersService,
    },
  ],
  exports: [UsersService, USER_SERVICE],
})
export class UsersPlatformModule {}
