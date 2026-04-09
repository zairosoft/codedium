import { Module } from '@nestjs/common';
import { UsersPlatformModule } from '../users/users.module';
import { AuthService } from './services/auth.service';

@Module({
  imports: [UsersPlatformModule],
  providers: [AuthService],
  exports: [AuthService],
})
export class AuthPlatformModule {}
