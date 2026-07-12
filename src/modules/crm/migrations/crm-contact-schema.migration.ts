import { QueryRunner } from 'typeorm';
import { WorklessMigration } from '../../../database/migration.interface';

export class CrmContactSchemaMigration implements WorklessMigration {
  readonly name = 'crm-contact-schema';
  readonly timestamp = 202607120100;
  readonly checksum = 'crm-contact-schema-v1';

  async up(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.query(`
      DO $$ BEGIN
        CREATE TYPE "crm_contacts_status_enum" AS ENUM ('lead', 'customer');
      EXCEPTION WHEN duplicate_object THEN NULL;
      END $$
    `);
    await queryRunner.query(`
      CREATE TABLE IF NOT EXISTS "crm_contacts" (
        "id" uuid PRIMARY KEY DEFAULT gen_random_uuid(),
        "tenantId" uuid NOT NULL,
        "orgId" uuid NOT NULL,
        "fullName" varchar(120) NOT NULL,
        "email" varchar(160) NOT NULL,
        "phone" varchar(30),
        "status" "crm_contacts_status_enum" NOT NULL DEFAULT 'lead',
        "createdAt" timestamptz NOT NULL DEFAULT now(),
        "updatedAt" timestamptz NOT NULL DEFAULT now(),
        "deletedAt" timestamptz
      )
    `);
    await queryRunner.query(`
      CREATE UNIQUE INDEX IF NOT EXISTS "uq_crm_contacts_tenant_email"
      ON "crm_contacts" ("tenantId", "email")
    `);
    await queryRunner.query(`
      CREATE INDEX IF NOT EXISTS "idx_crm_contacts_tenant"
      ON "crm_contacts" ("tenantId")
    `);
  }

  async down(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.query('DROP TABLE IF EXISTS "crm_contacts"');
    await queryRunner.query('DROP TYPE IF EXISTS "crm_contacts_status_enum"');
  }
}
