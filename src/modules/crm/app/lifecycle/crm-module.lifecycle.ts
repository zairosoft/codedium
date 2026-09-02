import { Injectable } from '@nestjs/common';
import { SystemModule } from '@/workless/module/module.decorator';
import {
  ModuleLifecycleContext,
  SystemModuleLifecycle,
} from '@/workless/module/module.interface';
import { CrmContactIndexMigration } from '@/modules/crm/database/migrations/crm-contact-index.migration';
import { CrmContactSchemaMigration } from '@/modules/crm/database/migrations/crm-contact-schema.migration';
import { CrmContactSeeder } from '@/modules/crm/database/seeders/crm-contact.seeder';

const MODULE_CACHE_VERSION_KEY = 'crm:module:version';

@SystemModule({
  name: 'crm',
  version: '1.0.0',
  description: 'CRM contacts, dashboards, hooks, and tenant-aware caching',
  migrations: [CrmContactSchemaMigration, CrmContactIndexMigration],
  seeders: [CrmContactSeeder],
})
@Injectable()
export class CrmModuleLifecycleService implements SystemModuleLifecycle {
  async install(context: ModuleLifecycleContext): Promise<void> {
    await this.bumpModuleCacheVersion(context);
  }

  async uninstall(context: ModuleLifecycleContext): Promise<void> {
    await this.bumpModuleCacheVersion(context);
  }

  async upgrade(context: ModuleLifecycleContext): Promise<void> {
    await this.bumpModuleCacheVersion(context);
  }

  private async bumpModuleCacheVersion(context: ModuleLifecycleContext): Promise<void> {
    await context.cacheService.set(MODULE_CACHE_VERSION_KEY, Date.now().toString());
  }
}
