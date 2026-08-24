import { QueryRunner, Table, TableIndex } from 'typeorm';

export class CreateCompaniesMigration {
  readonly name = 'create-companies';
  readonly timestamp = 202607120003;
  readonly checksum = 'create-companies-v1';

  async up(queryRunner: QueryRunner): Promise<void> {
    if (await queryRunner.hasTable('companies')) {
      return;
    }

    await queryRunner.createTable(
      new Table({
        name: 'companies',
        columns: [
          {
            name: 'id',
            type: 'uuid',
            isPrimary: true,
            isNullable: false,
            default: 'gen_random_uuid()',
          },
          {
            name: 'name',
            type: 'varchar',
            length: '160',
            isNullable: false,
          },
          {
            name: 'code',
            type: 'varchar',
            length: '80',
            isNullable: false,
          },
          {
            name: 'description',
            type: 'text',
            isNullable: true,
          },
          {
            name: 'logo',
            type: 'varchar',
            length: '500',
            isNullable: true,
          },
          {
            name: 'is_active',
            type: 'boolean',
            isNullable: false,
            default: true,
          },
          {
            name: 'created_at',
            type: 'timestamptz',
            isNullable: false,
            default: 'now()',
          },
          {
            name: 'created_by',
            type: 'uuid',
            isNullable: true,
          },
          {
            name: 'updated_at',
            type: 'timestamptz',
            isNullable: false,
            default: 'now()',
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
            name: 'uq_companies_code',
            columnNames: ['code'],
            isUnique: true,
          }),
          new TableIndex({
            name: 'idx_companies_name',
            columnNames: ['name'],
          }),
          new TableIndex({
            name: 'idx_companies_active_created_at',
            columnNames: ['created_at'],
            where: '"is_active" = true AND "deleted_at" IS NULL',
          }),
        ],
      }),
      true,
    );
  }

  async down(queryRunner: QueryRunner): Promise<void> {
    if (await queryRunner.hasTable('companies')) {
      await queryRunner.dropTable('companies', true, true);
    }
  }
}
