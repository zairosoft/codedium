import { Module } from '@nestjs/common';
import { AUTH_SERVICE } from '../../core/interfaces/auth.interface';
import { UsersPlatformModule } from '../users/users.module';
import { AuthService } from './services/auth.service';

@Module({
  imports: [UsersPlatformModule],
  providers: [
    AuthService,
    {
      provide: AUTH_SERVICE,
      useExisting: AuthService,
    },
  ],
  exports: [AuthService, AUTH_SERVICE],
})
export class AuthPlatformModule {}
