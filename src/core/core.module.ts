import { Global, Module } from '@nestjs/common';
import { APP_GUARD, APP_INTERCEPTOR, DiscoveryModule } from '@nestjs/core';
import { TypeOrmModule } from '@nestjs/typeorm';
import { HtmlCacheInterceptor } from './http/html-cache.interceptor';
import { HookService } from './events/hook.service';
import { ModuleLifecycleController } from './lifecycle/module-lifecycle.controller';
import { ModuleLifecycleService } from './lifecycle/module.lifecycle';
import { SystemModuleExplorer } from './system/system-module.explorer';
import { ModuleEnabledGuard } from './system/module-enabled.guard';
import { ModuleRegistryEntity } from './registry/module-registry.entity';
import { ModuleRegistryService } from './registry/module.registry';

@Global()
@Module({
  imports: [DiscoveryModule, TypeOrmModule.forFeature([ModuleRegistryEntity])],
  controllers: [ModuleLifecycleController],
  providers: [
    HookService,
    SystemModuleExplorer,
    ModuleRegistryService,
    ModuleLifecycleService,
    {
      provide: APP_GUARD,
      useClass: ModuleEnabledGuard,
    },
    {
      provide: APP_INTERCEPTOR,
      useClass: HtmlCacheInterceptor,
    },
  ],
  exports: [HookService, SystemModuleExplorer, ModuleRegistryService, ModuleLifecycleService],
})
export class CoreModule {}

