import { Module } from '@nestjs/common';
import { OrgModuleLifecycleService } from './app/lifecycle/org-module.lifecycle';

@Module({
  providers: [OrgModuleLifecycleService],
})
export class OrgModule {}
