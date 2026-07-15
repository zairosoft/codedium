import { Type } from '@nestjs/common';

export interface ModuleSeeder {
  readonly name: string;
  readonly order?: number;
  seed(): Promise<void>;
}

export type ModuleSeederConstructor = Type<ModuleSeeder>;
