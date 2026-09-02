import { Module } from '@nestjs/common';
import { HelpdeskModuleLifecycleService } from './app/lifecycle/helpdesk-module.lifecycle';

@Module({
  providers: [HelpdeskModuleLifecycleService],
})
export class HelpdeskModule {}
