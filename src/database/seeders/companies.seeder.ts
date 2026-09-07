import { DataSource } from 'typeorm';
import { DEFAULT_COMPANY_ID } from '@/workless/company/company.constants';
import { DatabaseSeeder } from '@/workless/interfaces/seeder.interface';

export class CompaniesSeeder implements DatabaseSeeder {
  readonly name = 'sample-companies';
  readonly order = 10;

  async seed(dataSource: DataSource): Promise<void> {
    const queryRunner = dataSource.createQueryRunner();
    await queryRunner.connect();

    try {
      if (!(await queryRunner.hasTable('companies'))) {
        throw new Error('Table "companies" does not exist. Run database migrations before seeding.');
      }

      await queryRunner.startTransaction();
      await queryRunner.query(
        `INSERT INTO "companies" (
          "id", "name", "code", "description", "logo", "is_active",
          "created_at", "updated_at"
        ) VALUES ($1, $2, $3, $4, NULL, true, now(), now())
        ON CONFLICT ("code") DO UPDATE SET
          "name" = EXCLUDED."name",
          "description" = EXCLUDED."description",
          "is_active" = EXCLUDED."is_active",
          "updated_at" = now()`,
        [
          DEFAULT_COMPANY_ID,
          'Workless System',
          'system',
          'Default company for system users and local development.',
        ],
      );

      await queryRunner.query(
        `INSERT INTO "companies" (
          "id", "name", "code", "description", "logo", "is_active",
          "created_at", "updated_at"
        ) VALUES ($1, $2, $3, $4, NULL, true, now(), now())
        ON CONFLICT ("code") DO UPDATE SET
          "name" = EXCLUDED."name",
          "description" = EXCLUDED."description",
          "is_active" = EXCLUDED."is_active",
          "updated_at" = now()`,
        [
          '0198a1b2-c3d4-7e5f-8a9b-0c1d2e3f4a5b',
          'Demo Company',
          'demo',
          'Sample company for local development.',
        ],
      );

      await queryRunner.commitTransaction();
    } catch (error) {
      if (queryRunner.isTransactionActive) {
        await queryRunner.rollbackTransaction();
      }
      throw error;
    } finally {
      await queryRunner.release();
    }
  }
}
