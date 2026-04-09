import { ConflictException, Injectable, Logger, NotFoundException } from '@nestjs/common';
import { DataSource } from 'typeorm';
import { CacheService } from '../infrastructure/cache/cache.service';
import { EventBusService } from '../events/event-bus.service';
import { HookService } from '../events/hook.service';
import { SystemModuleExplorer } from '../system/system-module.explorer';
import { ModuleLifecycleContext } from '../system/system-module.interface';
import { ModuleRegistryService } from '../registry/module.registry';

@Injectable()
export class ModuleLifecycleService {
  private readonly logger = new Logger(ModuleLifecycleService.name);

  constructor(
    private readonly moduleExplorer: SystemModuleExplorer,
    private readonly moduleRegistry: ModuleRegistryService,
    private readonly dataSource: DataSource,
    private readonly cacheService: CacheService,
    private readonly hookService: HookService,
    private readonly eventBus: EventBusService,
  ) {}

  async install(name: string) {
    const definition = this.getDefinitionOrFail(name);
    const currentState = await this.moduleRegistry.getOrFail(name);
    const context = this.createContext();

    if (currentState.enabled) {
      return currentState;
    }

    await this.ensureDependenciesEnabled(definition.metadata.dependencies);
    this.logger.log(`Installing module "${name}"`);
    await definition.instance.install(context);
    const result = await this.moduleRegistry.markInstalled(name, definition.metadata.version);
    await this.eventBus.emit('system.module.installed', {
      name,
      version: definition.metadata.version,
    });
    await this.eventBus.emit('notification.send', {
      name: 'system.module.installed',
      payload: {
        name,
        version: definition.metadata.version,
      },
      receivedAt: new Date().toISOString(),
    });
    return result;
  }

  async uninstall(name: string) {
    const definition = this.getDefinitionOrFail(name);
    const currentState = await this.moduleRegistry.getOrFail(name);
    const context = this.createContext();

    if (currentState.status === 'uninstalled') {
      return currentState;
    }

    await this.ensureNoEnabledDependents(name);
    this.logger.log(`Uninstalling module "${name}"`);
    await definition.instance.uninstall(context);
    await this.moduleRegistry.markDisabled(name);
    const result = await this.moduleRegistry.markUninstalled(name);
    await this.eventBus.emit('system.module.uninstalled', { name });
    await this.eventBus.emit('notification.send', {
      name: 'system.module.uninstalled',
      payload: { name },
      receivedAt: new Date().toISOString(),
    });
    return result;
  }

  async upgrade(name: string) {
    const definition = this.getDefinitionOrFail(name);
    const currentState = await this.moduleRegistry.getOrFail(name);
    const context = this.createContext();

    this.logger.log(
      `Upgrading module "${name}" from "${currentState.version}" to "${definition.metadata.version}"`,
    );
    await definition.instance.upgrade(context, currentState.version);
    const result = await this.moduleRegistry.markInstalled(name, definition.metadata.version);
    await this.eventBus.emit('system.module.upgraded', {
      name,
      fromVersion: currentState.version,
      toVersion: definition.metadata.version,
    });
    await this.eventBus.emit('notification.send', {
      name: 'system.module.upgraded',
      payload: {
        name,
        fromVersion: currentState.version,
        toVersion: definition.metadata.version,
      },
      receivedAt: new Date().toISOString(),
    });
    return result;
  }

  private getDefinitionOrFail(name: string) {
    const definition = this.moduleExplorer.getModule(name);
    if (!definition) {
      throw new NotFoundException(`System module "${name}" has no lifecycle provider.`);
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

  private async ensureDependenciesEnabled(dependencies: string[]): Promise<void> {
    for (const dependencyName of dependencies) {
      const enabled = await this.moduleRegistry.isEnabled(dependencyName);
      if (!enabled) {
        throw new ConflictException(
          `Module dependency "${dependencyName}" must be installed and enabled first.`,
        );
      }
    }
  }

  private async ensureNoEnabledDependents(name: string): Promise<void> {
    const registeredModules = await this.moduleRegistry.list();
    const enabledDependents = registeredModules.filter(
      (record) =>
        record.enabled &&
        record.status === 'installed' &&
        (record.dependencies ?? []).includes(name),
    );

    if (enabledDependents.length > 0) {
      throw new ConflictException(
        `Module "${name}" cannot be uninstalled while dependents are enabled: ${enabledDependents
          .map((record) => record.name)
          .join(', ')}`,
      );
    }
  }
}
