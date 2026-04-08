import { Injectable } from '@nestjs/common';
import { DataSource } from 'typeorm';
import { CacheService } from '../../infrastructure/cache/cache.service';
import { HookService } from '../events/hook.service';
import { SystemModuleExplorer } from '../system/system-module.explorer';
import { ModuleLifecycleContext } from '../system/system-module.interface';
import { ModuleRegistryService } from '../registry/module.registry';

@Injectable()
export class ModuleLifecycleService {
  constructor(
    private readonly moduleExplorer: SystemModuleExplorer,
    private readonly moduleRegistry: ModuleRegistryService,
    private readonly dataSource: DataSource,
    private readonly cacheService: CacheService,
    private readonly hookService: HookService,
  ) {}

  async install(name: string) {
    const definition = this.getDefinitionOrFail(name);
    const context = this.createContext();

    await definition.instance.install(context);
    return this.moduleRegistry.markInstalled(name, definition.metadata.version);
  }

  async uninstall(name: string) {
    const definition = this.getDefinitionOrFail(name);
    const context = this.createContext();

    await definition.instance.uninstall(context);
    await this.moduleRegistry.markDisabled(name);
    return this.moduleRegistry.markUninstalled(name);
  }

  async upgrade(name: string) {
    const definition = this.getDefinitionOrFail(name);
    const currentState = await this.moduleRegistry.getOrFail(name);
    const context = this.createContext();

    await definition.instance.upgrade(context, currentState.version);
    return this.moduleRegistry.markInstalled(name, definition.metadata.version);
  }

  private getDefinitionOrFail(name: string) {
    const definition = this.moduleExplorer.getModule(name);
    if (!definition) {
      throw new Error(`System module "${name}" has no lifecycle provider.`);
    }

    return definition;
  }

  private createContext(): ModuleLifecycleContext {
    return {
      dataSource: this.dataSource,
      cacheService: this.cacheService,
      hookService: this.hookService,
      moduleRegistry: this.moduleRegistry,
    };
  }
}

