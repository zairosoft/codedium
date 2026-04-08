import { Injectable } from '@nestjs/common';
import { SystemModule } from '../../../core/system/system-module.decorator';
import {
  ModuleLifecycleContext,
  SystemModuleLifecycle,
} from '../../../core/system/system-module.interface';

@SystemModule({
  name: 'permissions',
  version: '1.0.0',
  description: 'Permissions module registry placeholder',
})
@Injectable()
export class PermissionsModuleLifecycleService implements SystemModuleLifecycle {
  async install(_context: ModuleLifecycleContext): Promise<void> {}

  async uninstall(_context: ModuleLifecycleContext): Promise<void> {}

  async upgrade(_context: ModuleLifecycleContext): Promise<void> {}
}
