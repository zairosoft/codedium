import { DEFAULT_COMPANY_ID } from '@/workless/company/company.constants';
import { QueryRunner, Table, TableIndex } from 'typeorm';

export class CreateUsersMigration {
  readonly name = 'create-users';
  readonly timestamp = 202607120002;
  readonly checksum = 'create-users-v6-users-only';

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

  }

  async down(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.dropTable('users', true, true);
  }
}
