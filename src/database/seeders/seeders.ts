import { DatabaseSeeder } from './seeder.interface';
import { UsersSeeder } from './users.seeder';

export const databaseSeeders: DatabaseSeeder[] = [new UsersSeeder()].sort(
  (left, right) => (left.order ?? 0) - (right.order ?? 0),
);
