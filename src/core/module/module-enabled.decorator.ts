import { SetMetadata } from '@nestjs/common';

export const MODULE_ENABLED_METADATA = Symbol('MODULE_ENABLED_METADATA');

export function RequiresModule(moduleName: string): MethodDecorator & ClassDecorator {
  return SetMetadata(MODULE_ENABLED_METADATA, moduleName);
}

