import 'reflect-metadata';
import { NestFactory } from '@nestjs/core';
import { AppModule } from '../../app.module';
import { ModuleLifecycleService } from './module.lifecycle';
import { ModuleRegistryService } from '../registry/module.registry';

async function bootstrap(): Promise<void> {
  const app = await NestFactory.createApplicationContext(AppModule);

  try {
    const [command, target] = process.argv.slice(2);
    const lifecycle = app.get(ModuleLifecycleService);
    const registry = app.get(ModuleRegistryService);

    if (!command || command === 'list') {
      // eslint-disable-next-line no-console
      console.table(
        (await registry.list()).map((moduleState) => ({
          name: moduleState.name,
          version: moduleState.version,
          status: moduleState.status,
          enabled: moduleState.enabled,
        })),
      );
      return;
    }

    if (target === '--all') {
      const modules = await registry.list();
      for (const moduleState of modules) {
        if (command === 'install') {
          await lifecycle.install(moduleState.name);
        }

        if (command === 'upgrade') {
          await lifecycle.upgrade(moduleState.name);
        }
      }

      return;
    }

    if (!target) {
      throw new Error(`Missing module name for "${command}" command.`);
    }

    if (command === 'install') {
      await lifecycle.install(target);
      return;
    }

    if (command === 'upgrade') {
      await lifecycle.upgrade(target);
      return;
    }

    if (command === 'uninstall') {
      await lifecycle.uninstall(target);
      return;
    }

    throw new Error(`Unsupported command "${command}".`);
  } finally {
    await app.close();
  }
}

bootstrap().catch((error) => {
  // eslint-disable-next-line no-console
  console.error('Module lifecycle command failed', error);
  process.exit(1);
});

