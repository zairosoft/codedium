import { DEFAULT_COMPANY_ID } from '@/workless/company/company.constants';
import {
  QueryRunner,
  Table,
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

export class CreateUsersMigration {
  readonly name = 'create-users';
  readonly timestamp = 202607120002;
  readonly checksum = 'create-users-v5-company-id-columns';

  async up(queryRunner: QueryRunner): Promise<void> {
    const hasTable = await queryRunner.hasTable('users');
    if (!hasTable) {
      await queryRunner.createTable(
        new Table({
          name: 'users',
          columns: [
            {
              name: 'id',
              type: 'uuid',
              isPrimary: true,
              isNullable: false,
              default: 'gen_random_uuid()',
            },
            {
              name: 'company_id',
              type: 'uuid',
              isNullable: false,
              default: `'${DEFAULT_COMPANY_ID}'`,
            },
            {
              name: 'name',
              type: 'varchar',
              length: '120',
              isNullable: false,
            },
            {
              name: 'email',
              type: 'varchar',
              length: '160',
              isNullable: false,
            },
            {
              name: 'password',
              type: 'varchar',
              length: '255',
              isNullable: false,
            },
            {
              name: 'role',
              type: 'varchar',
              length: '50',
              isNullable: false,
            },
            {
              name: 'img',
              type: 'varchar',
              length: '500',
              isNullable: true,
            },
            {
              name: 'is_active',
              type: 'boolean',
              default: true,
              isNullable: false,
            },
            {
              name: 'locale',
              type: 'varchar',
              length: '10',
              default: "'en'",
              isNullable: false,
            },
            {
              name: 'last_logged_activities',
              type: 'text',
              isNullable: true,
            },
            {
              name: 'remember_token',
              type: 'varchar',
              length: '255',
              isNullable: true,
            },
            {
              name: 'email_verified_at',
              type: 'timestamptz',
              isNullable: true,
            },
            {
              name: 'last_logged_at',
              type: 'timestamptz',
              isNullable: true,
            },
            {
              name: 'created_at',
              type: 'timestamptz',
              default: 'now()',
              isNullable: false,
            },
            {
              name: 'created_by',
              type: 'uuid',
              isNullable: true,
            },
            {
              name: 'updated_at',
              type: 'timestamptz',
              default: 'now()',
              isNullable: false,
            },
            {
              name: 'updated_by',
              type: 'uuid',
              isNullable: true,
            },
            {
              name: 'deleted_at',
              type: 'timestamptz',
              isNullable: true,
            },
            {
              name: 'deleted_by',
              type: 'uuid',
              isNullable: true,
            },
          ],
          indices: [
            new TableIndex({
              name: 'uq_users_email',
              columnNames: ['email'],
              isUnique: true,
            }),
            new TableIndex({
              name: 'idx_users_company_id',
              columnNames: ['company_id'],
            }),
            new TableIndex({
              name: 'idx_users_role',
              columnNames: ['role'],
            }),
            new TableIndex({
              name: 'idx_users_created_at',
              columnNames: ['created_at'],
            }),
            new TableIndex({
              name: 'idx_users_active_created_at',
              columnNames: ['created_at'],
              where: '"is_active" = true AND "deleted_at" IS NULL',
            }),
          ],
        }),
        true,
      );
    }

      const hasMembershipsTable = await queryRunner.hasTable('user_memberships');
      if (!hasMembershipsTable) {
        await queryRunner.createTable(
          new Table({
            name: 'user_memberships',
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
                default: `'${DEFAULT_COMPANY_ID}'`,
              },
              {
                name: 'userId',
                type: 'uuid',
                isNullable: false,
              },
              {
                name: 'company_id',
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
                referencedTableName: 'users',
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
        'user_memberships',
        'uq_user_memberships_tenant_user_company_org',
      );
      if (!hasMembershipIndex) {
        await queryRunner.createIndex(
          'user_memberships',
          new TableIndex({
            name: 'uq_user_memberships_tenant_user_company_org',
            columnNames: ['tenantId', 'userId', 'company_id', 'organizationId'],
            isUnique: true,
          }),
        );
      }

      const hasCompanyIndex = await tableHasIndex(
        queryRunner,
        'user_memberships',
        'idx_user_memberships_tenant_company',
      );
      if (!hasCompanyIndex) {
        await queryRunner.createIndex(
          'user_memberships',
          new TableIndex({
            name: 'idx_user_memberships_tenant_company',
            columnNames: ['tenantId', 'company_id'],
          }),
        );
      }
  }

  async down(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.dropTable('user_memberships', true, true);
    await queryRunner.dropTable('users', true, true);
  }
}
