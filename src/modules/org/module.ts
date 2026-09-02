import { Module } from '@nestjs/common';
import { ConfigModule, registerAs } from '@nestjs/config';
import appConfig from './app.config.json';
import { OrgModuleLifecycleService } from './app/lifecycle/org-module.lifecycle';

export const orgConfig = registerAs('org', () => appConfig);

@Module({
  imports: [ConfigModule.forFeature(orgConfig)],
  providers: [OrgModuleLifecycleService],
  exports: [OrgModuleLifecycleService],
})
export class OrgModule {}
