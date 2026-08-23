import { Global, Module } from '@nestjs/common';
import { APP_GUARD, APP_INTERCEPTOR, DiscoveryModule } from '@nestjs/core';
import { TypeOrmModule } from '@nestjs/typeorm';
import { EVENT_BUS_PORT } from '../app/interfaces/event-bus.interface';
import { HOOK_PORT } from '../app/interfaces/hook.interface';
import { HtmlCacheInterceptor } from './http/html-cache.interceptor';
import { EventBusService } from './events/event-bus.service';
import { HookService } from './events/hook.service';
import { ModuleLifecycleController } from './lifecycle/module-lifecycle.controller';
import { ModuleLifecycleService } from './lifecycle/module.lifecycle';
import { ModuleSeedingService } from './lifecycle/module-seeding.service';
import { SystemModuleExplorer } from './module/module.explorer';
import { ModuleEnabledGuard } from './module/module-enabled.guard';
import { ModuleRegistryEntity } from './registry/module-registry.entity';
import { ModuleRegistryService } from './registry/module.registry';

@Global()
@Module({
  imports: [DiscoveryModule, TypeOrmModule.forFeature([ModuleRegistryEntity])],
  controllers: [ModuleLifecycleController],
  providers: [
    EventBusService,
    HookService,
    {
      provide: EVENT_BUS_PORT,
      useExisting: EventBusService,
    },
    {
      provide: HOOK_PORT,
      useExisting: HookService,
    },
    SystemModuleExplorer,
    ModuleRegistryService,
    ModuleLifecycleService,
    ModuleSeedingService,
    {
      provide: APP_GUARD,
      useClass: ModuleEnabledGuard,
    },
    {
      provide: APP_INTERCEPTOR,
      useClass: HtmlCacheInterceptor,
    },
  ],
  exports: [
    EventBusService,
    HookService,
    EVENT_BUS_PORT,
    HOOK_PORT,
    SystemModuleExplorer,
    ModuleRegistryService,
    ModuleLifecycleService,
    ModuleSeedingService,
  ],
})
export class WorklessModule {}
