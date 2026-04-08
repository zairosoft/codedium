import { DataSource } from 'typeorm';
import { HookService } from '../events/hook.service';
import { ModuleRegistryService } from '../registry/module.registry';
import { CacheService } from '../../infrastructure/cache/cache.service';

export type ModuleLifecycleContext = {
  dataSource: DataSource;
  cacheService: CacheService;
  hookService: HookService;
  moduleRegistry: ModuleRegistryService;
};

export interface SystemModuleLifecycle {
  install(context: ModuleLifecycleContext): Promise<void>;
  uninstall(context: ModuleLifecycleContext): Promise<void>;
  upgrade(context: ModuleLifecycleContext, fromVersion?: string): Promise<void>;
}

export interface ModuleMigration {
  name: string;
  run(context: ModuleLifecycleContext): Promise<void>;
}

export interface DiscoveredSystemModule {
  metadata: {
    name: string;
    version: string;
    description?: string;
    dependencies: string[];
  };
  instance: SystemModuleLifecycle;
}

