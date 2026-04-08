import { Injectable } from '@nestjs/common';
import { SystemModule } from '../../../core/system/system-module.decorator';
import {
  ModuleLifecycleContext,
  SystemModuleLifecycle,
} from '../../../core/system/system-module.interface';

@SystemModule({
  name: 'notifications',
  version: '1.0.0',
  description: 'Notifications module registry placeholder',
})
@Injectable()
export class NotificationsModuleLifecycleService implements SystemModuleLifecycle {
  async install(_context: ModuleLifecycleContext): Promise<void> {}

  async uninstall(_context: ModuleLifecycleContext): Promise<void> {}

  async upgrade(_context: ModuleLifecycleContext): Promise<void> {}
}
