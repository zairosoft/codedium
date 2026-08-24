import { DatabaseSeeder } from '../../workless/interfaces/seeder.interface';
import { CompaniesSeeder } from './companies.seeder';
import { UsersSeeder } from './users.seeder';

export const databaseSeeders: DatabaseSeeder[] = [new CompaniesSeeder(), new UsersSeeder()].sort(
  (left, right) => (left.order ?? 0) - (right.order ?? 0),
);
