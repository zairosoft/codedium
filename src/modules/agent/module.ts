import { Module } from '@nestjs/common';
import { AgentModuleLifecycleService } from './lifecycle/agent-module.lifecycle';

@Module({
  providers: [AgentModuleLifecycleService],
})
export class AgentModule {}
