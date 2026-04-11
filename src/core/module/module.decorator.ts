import { SetMetadata } from '@nestjs/common';

export const SYSTEM_MODULE_METADATA = Symbol('SYSTEM_MODULE_METADATA');

export type SystemModuleMetadata = {
  name: string;
  version: string;
  description?: string;
  dependencies?: string[];
};

export function SystemModule(metadata: SystemModuleMetadata): ClassDecorator {
  return SetMetadata(SYSTEM_MODULE_METADATA, metadata);
}

