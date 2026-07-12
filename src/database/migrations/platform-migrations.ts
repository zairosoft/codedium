import { WorklessMigrationConstructor } from '../migration.interface';
import { PlatformCoreSchemaMigration } from './platform-core-schema.migration';
import { PlatformUserSchemaMigration } from './platform-user-schema.migration';

export const platformMigrations: WorklessMigrationConstructor[] = [
  PlatformCoreSchemaMigration,
  PlatformUserSchemaMigration,
];
