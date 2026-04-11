import { DEFAULT_TENANT_ID } from '../../tenant/tenant.constants';
import {
  DataSource,
  QueryRunner,
  Table,
  TableColumn,
  TableForeignKey,
  TableIndex,
} from 'typeorm';

async function tableHasIndex(
  queryRunner: QueryRunner,
  tableName: string,
  indexName: string,
): Promise<boolean> {
  const table = await queryRunner.getTable(tableName);
  return table?.indices.some((index) => index.name === indexName) ?? false;
}

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

      const hasLegacyEmailIndex = await tableHasIndex(
        queryRunner,
        'platform_users',
        'uq_platform_users_email',
      );
      if (hasLegacyEmailIndex) {
        await queryRunner.dropIndex('platform_users', 'uq_platform_users_email');
      }

      const hasTenantEmailIndex = await tableHasIndex(
        queryRunner,
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

      const hasMembershipsTable = await queryRunner.hasTable('platform_user_memberships');
      if (!hasMembershipsTable) {
        await queryRunner.createTable(
          new Table({
            name: 'platform_user_memberships',
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
                name: 'userId',
                type: 'uuid',
                isNullable: false,
              },
              {
                name: 'organizationId',
                type: 'uuid',
                isNullable: false,
              },
              {
                name: 'roleCode',
                type: 'varchar',
                length: '80',
                isNullable: false,
              },
              {
                name: 'isDefault',
                type: 'boolean',
                default: false,
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
            foreignKeys: [
              new TableForeignKey({
                columnNames: ['userId'],
                referencedTableName: 'platform_users',
                referencedColumnNames: ['id'],
                onDelete: 'CASCADE',
              }),
            ],
          }),
          true,
        );
      }

      const hasMembershipIndex = await tableHasIndex(
        queryRunner,
        'platform_user_memberships',
        'uq_platform_user_memberships_tenant_user_org',
      );
      if (!hasMembershipIndex) {
        await queryRunner.createIndex(
          'platform_user_memberships',
          new TableIndex({
            name: 'uq_platform_user_memberships_tenant_user_org',
            columnNames: ['tenantId', 'userId', 'organizationId'],
            isUnique: true,
          }),
        );
      }
    } finally {
      await queryRunner.release();
    }
  }
}
