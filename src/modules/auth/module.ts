import { Module } from '@nestjs/common';
import { AuthModuleLifecycleService } from './lifecycle/auth-module.lifecycle';

@Module({
  providers: [AuthModuleLifecycleService],
})
export class AuthModule {}
