import { Injectable } from '@nestjs/common';
import { SystemModule } from '../../../core/module/module.decorator';
import {
  ModuleLifecycleContext,
  SystemModuleLifecycle,
} from '../../../core/module/module.interface';

@SystemModule({
  name: 'org',
  version: '1.0.0',
  description: 'Organization module registry placeholder',
})
@Injectable()
export class OrgModuleLifecycleService implements SystemModuleLifecycle {
  async install(_context: ModuleLifecycleContext): Promise<void> {}

  async uninstall(_context: ModuleLifecycleContext): Promise<void> {}

  async upgrade(_context: ModuleLifecycleContext): Promise<void> {}
}
