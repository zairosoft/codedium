import { DataSource } from 'typeorm';
import { WorklessMigrationConstructor } from '../../database/migration.interface';
import { CachePort } from '../interfaces/cache.interface';
import { HookPort } from '../interfaces/hook.interface';
import { ModuleRegistryService } from '../registry/module.registry';

export type ModuleLifecycleContext = {
  dataSource: DataSource;
  cacheService: CachePort;
  hookService: HookPort;
  moduleRegistry: ModuleRegistryService;
};

export interface SystemModuleLifecycle {
  install(context: ModuleLifecycleContext): Promise<void>;
  uninstall(context: ModuleLifecycleContext): Promise<void>;
  upgrade(context: ModuleLifecycleContext, fromVersion?: string): Promise<void>;
}

export interface DiscoveredSystemModule {
  metadata: {
    name: string;
    version: string;
    description?: string;
    dependencies: string[];
    migrations: WorklessMigrationConstructor[];
  };
  instance: SystemModuleLifecycle;
}

