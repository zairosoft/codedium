import 'reflect-metadata';
import { NestFactory } from '@nestjs/core';
import { DataSource } from 'typeorm';
import { AppModule } from '../app.module';
import { ModuleLifecycleService } from '../core/lifecycle/module.lifecycle';
import { ModuleRegistryService } from '../core/registry/module.registry';
import { PlatformUserSchemaMigration } from './migrations/platform-user-schema.migration';

async function runSeeders() {
  const app = await NestFactory.createApplicationContext(AppModule);
  try {
    const dataSource = app.get(DataSource);
    const moduleLifecycle = app.get(ModuleLifecycleService);
    const moduleRegistry = app.get(ModuleRegistryService);
    const platformUserSchemaMigration = new PlatformUserSchemaMigration();

    await platformUserSchemaMigration.run(dataSource);

    const modules = await moduleRegistry.list();
    for (const moduleState of modules) {
      await moduleLifecycle.install(moduleState.name);
    }
  } finally {
    await app.close();
  }
}

runSeeders()
  .then(() => {
    console.log('Seeders completed');
  })
  .catch((error) => {
    console.error('Seeder failed', error);
    process.exit(1);
  });
