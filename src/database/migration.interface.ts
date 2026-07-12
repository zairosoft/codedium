import { QueryRunner } from 'typeorm';

export type MigrationScope = 'platform' | 'module';

export interface WorklessMigration {
  readonly name: string;
  readonly timestamp: number;
  readonly checksum: string;
  readonly transaction?: boolean;
  up(queryRunner: QueryRunner): Promise<void>;
  down?(queryRunner: QueryRunner): Promise<void>;
}

export type WorklessMigrationConstructor = new () => WorklessMigration;

export type MigrationStatus = {
  name: string;
  timestamp: number;
  applied: boolean;
  executedAt?: Date;
};
