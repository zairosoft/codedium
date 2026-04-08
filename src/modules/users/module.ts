import { Module } from '@nestjs/common';
import { UsersModuleLifecycleService } from './lifecycle/users-module.lifecycle';

@Module({
  providers: [UsersModuleLifecycleService],
})
export class UsersModule {}
