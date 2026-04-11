import 'reflect-metadata';
import { NestFactory } from '@nestjs/core';
import { DataSource } from 'typeorm';
import { AppModule } from '../app.module';
import { PlatformUserSchemaMigration } from './migrations/platform-user-schema.migration';

async function runPlatformSchema(): Promise<void> {
  const app = await NestFactory.createApplicationContext(AppModule);

  try {
    const dataSource = app.get(DataSource);
    const platformUserSchemaMigration = new PlatformUserSchemaMigration();
    await platformUserSchemaMigration.run(dataSource);
  } finally {
    await app.close();
  }
}

runPlatformSchema()
  .then(() => {
    console.log('Platform schema completed');
  })
  .catch((error) => {
    console.error('Platform schema failed', error);
    process.exit(1);
  });
