import { Module } from '@nestjs/common';
import { PermissionsModuleLifecycleService } from './lifecycle/permissions-module.lifecycle';

@Module({
  providers: [PermissionsModuleLifecycleService],
})
export class PermissionsModule {}
