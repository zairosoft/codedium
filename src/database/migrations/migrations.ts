import { WorklessMigrationConstructor } from '../migration.interface';
import { CreateModuleRegistryMigration } from './202607120001-create-module-registry.migration';
import { CreateUsersMigration } from './202607120002-create-users.migration';

export const migrations: WorklessMigrationConstructor[] = [
  CreateModuleRegistryMigration,
  CreateUsersMigration,
];
