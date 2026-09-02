import { QueryRunner } from 'typeorm';
import { WorklessMigration } from '@/database/migration.interface';

export class CreateModuleRegistryMigration implements WorklessMigration {
  readonly name = 'create-module-registry';
  readonly timestamp = 202607120001;
  readonly checksum = 'create-module-registry-v1';

  async up(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.query(`
      CREATE TABLE IF NOT EXISTS "module_registries" (
        "id" uuid PRIMARY KEY DEFAULT gen_random_uuid(),
        "name" varchar(80) NOT NULL,
        "version" varchar(32) NOT NULL DEFAULT '0.0.0',
        "availableVersion" varchar(32),
        "status" varchar(20) NOT NULL DEFAULT 'uninstalled',
        "enabled" boolean NOT NULL DEFAULT false,
        "description" varchar(255),
        "dependencies" text,
        "metadata" text,
        "installedAt" timestamptz,
        "upgradedAt" timestamptz,
        "createdAt" timestamptz NOT NULL DEFAULT now(),
        "updatedAt" timestamptz NOT NULL DEFAULT now()
      )
    `);
    await queryRunner.query(`
      CREATE UNIQUE INDEX IF NOT EXISTS "uq_module_registries_name"
      ON "module_registries" ("name")
    `);
    await queryRunner.query(`
      ALTER TABLE "module_registries"
      ADD COLUMN IF NOT EXISTS "availableVersion" varchar(32)
    `);
  }

  async down(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.query('DROP TABLE IF EXISTS "module_registries"');
  }
}
