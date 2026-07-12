import { Injectable } from '@nestjs/common';
import { DiscoveryService, Reflector } from '@nestjs/core';
import { SYSTEM_MODULE_METADATA, SystemModuleMetadata } from './module.decorator';
import { DiscoveredSystemModule, SystemModuleLifecycle } from './module.interface';

@Injectable()
export class SystemModuleExplorer {
  private discoveredModules?: DiscoveredSystemModule[];

  constructor(
    private readonly discoveryService: DiscoveryService,
    private readonly reflector: Reflector,
  ) {}

  getModules(): DiscoveredSystemModule[] {
    if (!this.discoveredModules) {
      this.discoveredModules = this.scanModules();
    }

    return this.discoveredModules;
  }

  getModule(name: string): DiscoveredSystemModule | undefined {
    return this.getModules().find((definition) => definition.metadata.name === name);
  }

  private scanModules(): DiscoveredSystemModule[] {
    const modules: DiscoveredSystemModule[] = [];

    for (const wrapper of this.discoveryService.getProviders()) {
      const instance = wrapper.instance as SystemModuleLifecycle | undefined;
      if (!instance) {
        continue;
      }

      const metadata = this.reflector.get<SystemModuleMetadata>(
        SYSTEM_MODULE_METADATA,
        instance.constructor,
      );

      if (!metadata) {
        continue;
      }

      modules.push({
        metadata: {
          ...metadata,
          dependencies: metadata.dependencies ?? [],
          migrations: metadata.migrations ?? [],
        },
        instance,
      });
    }

    return modules;
  }
}

