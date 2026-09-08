import { Global, Module } from '@nestjs/common';
import { APP_GUARD, DiscoveryModule } from '@nestjs/core';
import { TypeOrmModule } from '@nestjs/typeorm';
import { EVENT_BUS_PORT } from '@/app/interfaces/event-bus.interface';
import { HOOK_PORT } from '@/app/interfaces/hook.interface';
import { EventBusService } from '@/workless/events/event-bus.service';
import { HookService } from '@/workless/events/hook.service';
import { ModuleLifecycleController } from '@/workless/lifecycle/module-lifecycle.controller';
import { ModuleLifecycleService } from '@/workless/lifecycle/module.lifecycle';
import { ModuleSeedingService } from '@/workless/lifecycle/module-seeding.service';
import { SystemModuleExplorer } from '@/workless/module/module.explorer';
import { ModuleEnabledGuard } from '@/workless/module/module-enabled.guard';
import { ModuleRegistryEntity } from '@/workless/registry/module-registry.entity';
import { ModuleRegistryService } from '@/workless/registry/module.registry';

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
