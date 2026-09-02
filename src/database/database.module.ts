import { Global, Module } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import { TypeOrmModule } from '@nestjs/typeorm';
import { createTypeOrmConfig } from '@/config/typeorm.config';
import { MigrationService } from '@/database/migration.service';

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
