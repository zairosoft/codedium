import { SetMetadata } from '@nestjs/common';

export const HOOK_METADATA = Symbol('HOOK_METADATA');

export function Hook(name: string): MethodDecorator {
  return SetMetadata(HOOK_METADATA, name);
}

