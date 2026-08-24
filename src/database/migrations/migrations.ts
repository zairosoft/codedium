import { WorklessMigrationConstructor } from '../migration.interface';
import { CreateModuleRegistryMigration } from './202607120001-create-module-registry.migration';
import { CreateUsersMigration } from './202607120002-create-users.migration';
import { CreateCompaniesMigration } from './202607120003-create-companies.migration';

export const migrations: WorklessMigrationConstructor[] = [
  CreateModuleRegistryMigration,
  CreateUsersMigration,
  CreateCompaniesMigration,
];
