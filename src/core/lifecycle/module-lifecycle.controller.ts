import { Controller, Get, Param, Post } from '@nestjs/common';
import { ModuleLifecycleService } from './module.lifecycle';
import { ModuleRegistryService } from '../registry/module.registry';

@Controller('modules')
export class ModuleLifecycleController {
  constructor(
    private readonly moduleRegistry: ModuleRegistryService,
    private readonly moduleLifecycle: ModuleLifecycleService,
  ) {}

  @Get()
  list() {
    return this.moduleRegistry.list();
  }

  @Post(':name/install')
  install(@Param('name') name: string) {
    return this.moduleLifecycle.install(name);
  }

  @Post(':name/uninstall')
  uninstall(@Param('name') name: string) {
    return this.moduleLifecycle.uninstall(name);
  }

  @Post(':name/upgrade')
  upgrade(@Param('name') name: string) {
    return this.moduleLifecycle.upgrade(name);
  }
}

