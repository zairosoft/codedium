import { Module } from '@nestjs/common';
import { USER_SERVICE } from '../interfaces/user.interface';
import { UsersService } from './services/users.service';

@Module({
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
