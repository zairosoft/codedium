import { QueryRunner } from 'typeorm';
import { WorklessMigration } from '@/database/migration.interface';

export class CrmContactIndexMigration implements WorklessMigration {
  readonly name = 'crm-contact-index-tenant-status';
  readonly timestamp = 202607120101;
  readonly checksum = 'crm-contact-index-tenant-status-v1';

  async up(queryRunner: QueryRunner): Promise<void> {
    const tableExists = await queryRunner.hasTable('crm_contacts');
    if (!tableExists) return;

    await queryRunner.query(
      'CREATE INDEX IF NOT EXISTS "idx_crm_contacts_tenant_status" ON "crm_contacts" ("tenantId", "status")',
    );
  }

  async down(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.query('DROP INDEX IF EXISTS "idx_crm_contacts_tenant_status"');
  }
}
