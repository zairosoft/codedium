import 'reflect-metadata';
import { NestFactory } from '@nestjs/core';
import { AppModule } from '../app.module';
import { ModuleLifecycleService } from '../workless/lifecycle/module.lifecycle';
import { ModuleRegistryService } from '../workless/registry/module.registry';

async function runSeeders() {
  const app = await NestFactory.createApplicationContext(AppModule);
  try {
    const moduleLifecycle = app.get(ModuleLifecycleService);
    const moduleRegistry = app.get(ModuleRegistryService);
    const modules = await moduleRegistry.list();
    for (const moduleState of modules) {
      await moduleLifecycle.seed(moduleState.name);
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
