import { ModuleLifecycleContext, ModuleMigration } from '../../../core/system/system-module.interface';

export class CrmContactIndexMigration implements ModuleMigration {
  name = 'crm-contact-index-tenant-status';

  async run({ dataSource }: ModuleLifecycleContext): Promise<void> {
    const queryRunner = dataSource.createQueryRunner();
    await queryRunner.connect();

    try {
      const tableExists = await queryRunner.hasTable('crm_contacts');
      if (!tableExists) {
        return;
      }

      await queryRunner.query(
        'CREATE INDEX IF NOT EXISTS "idx_crm_contacts_tenant_status" ON "crm_contacts" ("tenantId", "status")',
      );
    } finally {
      await queryRunner.release();
    }
  }
}

