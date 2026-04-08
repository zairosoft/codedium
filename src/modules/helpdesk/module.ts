import { Module } from '@nestjs/common';
import { HelpdeskModuleLifecycleService } from './lifecycle/helpdesk-module.lifecycle';

@Module({
  providers: [HelpdeskModuleLifecycleService],
})
export class HelpdeskModule {}
