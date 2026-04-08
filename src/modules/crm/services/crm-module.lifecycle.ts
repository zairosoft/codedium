import { Injectable } from '@nestjs/common';
import { SystemModule } from '../../../core/system/system-module.decorator';
import {
  SystemModuleLifecycle,
  ModuleLifecycleContext,
} from '../../../core/system/system-module.interface';
import { CrmContactIndexMigration } from '../migrations/crm-contact-index.migration';
import { CrmContactSeeder } from '../seeders/crm-contact.seeder';

@SystemModule({
  name: 'crm',
  version: '1.0.0',
  description: 'CRM contacts, dashboards, hooks, and tenant-aware caching',
})
@Injectable()
export class CrmModuleLifecycleService implements SystemModuleLifecycle {
  private readonly migrations = [new CrmContactIndexMigration()];

  constructor(private readonly seeder: CrmContactSeeder) {}

  async install(context: ModuleLifecycleContext): Promise<void> {
    for (const migration of this.migrations) {
      await migration.run(context);
    }

    await this.seeder.seed();
    await context.cacheService.del('crm:public:contacts:version');
  }

  async uninstall(context: ModuleLifecycleContext): Promise<void> {
    await context.cacheService.del('crm:public:contacts:version');
  }

  async upgrade(context: ModuleLifecycleContext): Promise<void> {
    for (const migration of this.migrations) {
      await migration.run(context);
    }

    await context.cacheService.del('crm:public:contacts:version');
  }
}

