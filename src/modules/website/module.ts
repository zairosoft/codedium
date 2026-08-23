import { Module } from '@nestjs/common';
import { WebsiteModuleLifecycleService } from './lifecycle/website-module.lifecycle';

@Module({
  providers: [WebsiteModuleLifecycleService],
})
export class WebsiteModule {}
