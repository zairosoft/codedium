import { QueryRunner } from 'typeorm';
import { WorklessMigration } from '../migration.interface';

export class PlatformCoreSchemaMigration implements WorklessMigration {
  readonly name = 'platform-core-schema';
  readonly timestamp = 202607120001;
  readonly checksum = 'platform-core-schema-v1';

  async up(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.query(`
      CREATE TABLE IF NOT EXISTS "system_module_registry" (
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
      CREATE UNIQUE INDEX IF NOT EXISTS "IDX_system_module_registry_name"
      ON "system_module_registry" ("name")
    `);
    await queryRunner.query(`
      ALTER TABLE "system_module_registry"
      ADD COLUMN IF NOT EXISTS "availableVersion" varchar(32)
    `);
  }

  async down(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.query('DROP TABLE IF EXISTS "system_module_registry"');
  }
}
