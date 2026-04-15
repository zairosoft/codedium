import { Controller, Get, Param, Post, UseGuards } from '@nestjs/common';
import { PermissionGuard } from '../../app/providers/permission.guard';
import { RequirePermissions } from '../../app/providers/require-permissions.decorator';
import { ModuleLifecycleService } from './module.lifecycle';
import { ModuleRegistryService } from '../registry/module.registry';

@Controller('modules')
@UseGuards(PermissionGuard)
export class ModuleLifecycleController {
  constructor(
    private readonly moduleRegistry: ModuleRegistryService,
    private readonly moduleLifecycle: ModuleLifecycleService,
  ) {}

  @Get()
  @RequirePermissions('platform.user.read')
  list() {
    return this.moduleRegistry.list();
  }

  @Post(':name/install')
  @RequirePermissions('system.module.install')
  install(@Param('name') name: string) {
    return this.moduleLifecycle.install(name);
  }

  @Post(':name/uninstall')
  @RequirePermissions('system.module.uninstall')
  uninstall(@Param('name') name: string) {
    return this.moduleLifecycle.uninstall(name);
  }

  @Post(':name/upgrade')
  @RequirePermissions('system.module.upgrade')
  upgrade(@Param('name') name: string) {
    return this.moduleLifecycle.upgrade(name);
  }
}
