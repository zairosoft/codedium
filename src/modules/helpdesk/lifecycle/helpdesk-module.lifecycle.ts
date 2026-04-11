import { Injectable } from '@nestjs/common';
import { SystemModule } from '../../../core/module/module.decorator';
import {
  ModuleLifecycleContext,
  SystemModuleLifecycle,
} from '../../../core/module/module.interface';

@SystemModule({
  name: 'helpdesk',
  version: '1.0.0',
  description: 'Helpdesk module registry placeholder',
})
@Injectable()
export class HelpdeskModuleLifecycleService implements SystemModuleLifecycle {
  async install(_context: ModuleLifecycleContext): Promise<void> {}

  async uninstall(_context: ModuleLifecycleContext): Promise<void> {}

  async upgrade(_context: ModuleLifecycleContext): Promise<void> {}
}
