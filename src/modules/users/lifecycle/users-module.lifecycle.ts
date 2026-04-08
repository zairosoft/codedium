import { Injectable } from '@nestjs/common';
import { SystemModule } from '../../../core/system/system-module.decorator';
import {
  ModuleLifecycleContext,
  SystemModuleLifecycle,
} from '../../../core/system/system-module.interface';

@SystemModule({
  name: 'users',
  version: '1.0.0',
  description: 'Users module registry placeholder',
})
@Injectable()
export class UsersModuleLifecycleService implements SystemModuleLifecycle {
  async install(_context: ModuleLifecycleContext): Promise<void> {}

  async uninstall(_context: ModuleLifecycleContext): Promise<void> {}

  async upgrade(_context: ModuleLifecycleContext): Promise<void> {}
}
