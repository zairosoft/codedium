import {
  CanActivate,
  ExecutionContext,
  Injectable,
  ServiceUnavailableException,
} from '@nestjs/common';
import { Reflector } from '@nestjs/core';
import { MODULE_ENABLED_METADATA } from '@/workless/module/module-enabled.decorator';
import { ModuleRegistryService } from '@/workless/registry/module.registry';

@Injectable()
export class ModuleEnabledGuard implements CanActivate {
  constructor(
    private readonly reflector: Reflector,
    private readonly moduleRegistry: ModuleRegistryService,
  ) {}

  async canActivate(context: ExecutionContext): Promise<boolean> {
    const moduleName = this.reflector.getAllAndOverride<string | undefined>(
      MODULE_ENABLED_METADATA,
      [context.getHandler(), context.getClass()],
    );

    if (!moduleName) {
      return true;
    }

    const enabled = await this.moduleRegistry.isEnabled(moduleName);
    if (!enabled) {
      throw new ServiceUnavailableException(
        `Module "${moduleName}" is installed but disabled or not installed yet.`,
      );
    }

    return true;
  }
}

