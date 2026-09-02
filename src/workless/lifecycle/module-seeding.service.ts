import { Injectable, Logger } from '@nestjs/common';
import { ModuleRef } from '@nestjs/core';
import { ModuleSeeder, ModuleSeederConstructor } from '@/workless/lifecycle/module-seeder.interface';

export type ModuleSeedResult = {
  moduleName: string;
  seeder: string;
  executionMs: number;
};

@Injectable()
export class ModuleSeedingService {
  private readonly logger = new Logger(ModuleSeedingService.name);

  constructor(private readonly moduleRef: ModuleRef) {}

  async seed(
    moduleName: string,
    constructors: ModuleSeederConstructor[],
  ): Promise<ModuleSeedResult[]> {
    const seeders = this.resolveSeeders(constructors);
    const results: ModuleSeedResult[] = [];

    for (const seeder of seeders) {
      const startedAt = Date.now();
      await seeder.seed();
      const result = {
        moduleName,
        seeder: seeder.name,
        executionMs: Date.now() - startedAt,
      };
      results.push(result);
      this.logger.log(`Completed seeder "${seeder.name}" for module "${moduleName}"`);
    }

    return results;
  }

  private resolveSeeders(constructors: ModuleSeederConstructor[]): ModuleSeeder[] {
    const seeders = constructors.map((Seeder) => this.moduleRef.get(Seeder, { strict: false }));
    const names = new Set<string>();

    for (const seeder of seeders) {
      if (!seeder) {
        throw new Error('Module seeder is not registered as a Nest provider.');
      }
      if (!seeder.name) {
        throw new Error('Every module seeder requires a name.');
      }
      if (names.has(seeder.name)) {
        throw new Error(`Duplicate module seeder name "${seeder.name}".`);
      }
      names.add(seeder.name);
    }

    return seeders.sort((left, right) => {
      const orderDifference = (left.order ?? 0) - (right.order ?? 0);
      return orderDifference || left.name.localeCompare(right.name);
    });
  }
}
