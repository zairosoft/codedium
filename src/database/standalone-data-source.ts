import 'reflect-metadata';
import { config } from 'dotenv';
import { DataSource } from 'typeorm';

config({ path: '.env.local' });
config({ path: '.env' });

export function createStandaloneDataSource(): DataSource {
  return new DataSource({
    type: 'postgres',
    host: process.env.DB_HOST ?? 'localhost',
    port: Number(process.env.DB_PORT ?? 5432),
    username: process.env.DB_USERNAME ?? 'postgres',
    password: process.env.DB_PASSWORD ?? 'postgres',
    database: process.env.DB_NAME ?? 'workless',
    synchronize: false,
    logging: process.env.NODE_ENV !== 'production',
  });
}
