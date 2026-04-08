import { Module } from '@nestjs/common';
import { OrgModuleLifecycleService } from './lifecycle/org-module.lifecycle';

@Module({
  providers: [OrgModuleLifecycleService],
})
export class OrgModule {}
