import 'reflect-metadata';
import { MigrationService } from './migration.service';
import { migrations } from './migrations/migrations';
import { createStandaloneDataSource } from '../config/env.config';

async function runPlatformSchema(): Promise<void> {
  const dataSource = createStandaloneDataSource();
  await dataSource.initialize();

  try {
    const migrationService = new MigrationService(dataSource);
    const [command = 'migrate'] = process.argv.slice(2);
    if (command === 'migrate') {
      await migrationService.migratePlatform(migrations);
      return;
    }
    if (command === 'status') {
      console.table(await migrationService.status('platform', null, migrations));
      return;
    }
    if (command === 'revert') {
      const reverted = await migrationService.revertLast('platform', null, migrations);
      console.log(reverted ? `Reverted migration: ${reverted}` : 'No applied migration to revert.');
      return;
    }
    throw new Error(`Unsupported database migration command "${command}".`);
  } finally {
    await dataSource.destroy();
  }
}

runPlatformSchema()
  .then(() => {
    console.log('Platform migration command completed');
  })
  .catch((error) => {
    console.error('Platform schema failed', error);
    process.exit(1);
  });
