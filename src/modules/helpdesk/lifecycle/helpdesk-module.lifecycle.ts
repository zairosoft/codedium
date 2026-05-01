import { Injectable } from '@nestjs/common';
import { SystemModule } from '../../../workless/module/module.decorator';
import {
  ModuleLifecycleContext,
  SystemModuleLifecycle,
} from '../../../workless/module/module.interface';

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
