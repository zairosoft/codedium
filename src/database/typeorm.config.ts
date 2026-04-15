import { ConfigService } from '@nestjs/config';
import { TypeOrmModuleOptions } from '@nestjs/typeorm';

export function createTypeOrmConfig(configService: ConfigService): TypeOrmModuleOptions {
  const isProduction = configService.get<string>('NODE_ENV', 'development') === 'production';

  return {
    type: 'postgres',
    host: configService.get<string>('DB_HOST', 'localhost'),
    port: Number(configService.get<number>('DB_PORT', 5432)),
    username: configService.get<string>('DB_USERNAME', 'postgres'),
    password: configService.get<string>('DB_PASSWORD', 'postgres'),
    database: configService.get<string>('DB_NAME', 'zairosoft'),
    autoLoadEntities: true,
    // SECURITY: Never synchronize in production — use migrations instead
    synchronize: !isProduction && configService.get<string>('DB_SYNC', 'false') === 'true',
    logging: !isProduction,
  };
}
