import { Global, Module } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import { TypeOrmModule } from '@nestjs/typeorm';
import { createTypeOrmConfig } from './typeorm.config';
import { MigrationService } from './migration.service';

@Global()
@Module({
  imports: [
    TypeOrmModule.forRootAsync({
      inject: [ConfigService],
      useFactory: (configService: ConfigService) => createTypeOrmConfig(configService),
    }),
  ],
  providers: [MigrationService],
  exports: [TypeOrmModule, MigrationService],
})
export class DatabaseModule {}
