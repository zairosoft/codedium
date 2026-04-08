import { Module } from '@nestjs/common';
import { AppsModuleLifecycleService } from './lifecycle/apps-module.lifecycle';

@Module({
  providers: [AppsModuleLifecycleService],
})
export class AppsModule {}
