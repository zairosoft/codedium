import { DEFAULT_TENANT_ID } from '../../tenant/tenant.constants';
import { DataSource, Table, TableColumn, TableIndex } from 'typeorm';

export class PlatformUserSchemaMigration {
  async run(dataSource: DataSource): Promise<void> {
    const queryRunner = dataSource.createQueryRunner();
    await queryRunner.connect();

    try {
      const hasTable = await queryRunner.hasTable('platform_users');
      if (!hasTable) {
        await queryRunner.createTable(
          new Table({
            name: 'platform_users',
            columns: [
              {
                name: 'id',
                type: 'uuid',
                isPrimary: true,
                isNullable: false,
              },
              {
                name: 'tenantId',
                type: 'uuid',
                isNullable: false,
                default: `'${DEFAULT_TENANT_ID}'`,
              },
              {
                name: 'email',
                type: 'varchar',
                length: '160',
                isNullable: false,
              },
              {
                name: 'displayName',
                type: 'varchar',
                length: '120',
                isNullable: false,
              },
              {
                name: 'active',
                type: 'boolean',
                default: true,
                isNullable: false,
              },
              {
                name: 'roles',
                type: 'jsonb',
                default: "'[]'::jsonb",
                isNullable: false,
              },
              {
                name: 'createdAt',
                type: 'timestamptz',
                default: 'now()',
                isNullable: false,
              },
              {
                name: 'updatedAt',
                type: 'timestamptz',
                default: 'now()',
                isNullable: false,
              },
              {
                name: 'deletedAt',
                type: 'timestamptz',
                isNullable: true,
              },
            ],
          }),
          true,
        );
      }

      const hasTenantColumn = await queryRunner.hasColumn('platform_users', 'tenantId');
      if (!hasTenantColumn) {
        await queryRunner.addColumn(
          'platform_users',
          new TableColumn({
            name: 'tenantId',
            type: 'uuid',
            isNullable: false,
            default: `'${DEFAULT_TENANT_ID}'`,
          }),
        );
      }

      const hasDeletedAtColumn = await queryRunner.hasColumn('platform_users', 'deletedAt');
      if (!hasDeletedAtColumn) {
        await queryRunner.addColumn(
          'platform_users',
          new TableColumn({
            name: 'deletedAt',
            type: 'timestamptz',
            isNullable: true,
          }),
        );
      }

      const hasLegacyEmailIndex = await queryRunner.hasIndex(
        'platform_users',
        'uq_platform_users_email',
      );
      if (hasLegacyEmailIndex) {
        await queryRunner.dropIndex('platform_users', 'uq_platform_users_email');
      }

      const hasTenantEmailIndex = await queryRunner.hasIndex(
        'platform_users',
        'uq_platform_users_tenant_email',
      );
      if (!hasTenantEmailIndex) {
        await queryRunner.createIndex(
          'platform_users',
          new TableIndex({
            name: 'uq_platform_users_tenant_email',
            columnNames: ['tenantId', 'email'],
            isUnique: true,
          }),
        );
      }
    } finally {
      await queryRunner.release();
    }
  }
}
