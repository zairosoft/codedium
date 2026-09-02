import { Module } from '@nestjs/common';
import { ConfigModule, registerAs } from '@nestjs/config';
import { TypeOrmModule } from '@nestjs/typeorm';
import crmAppConfig from './app.config.json';
import { CrmContactController } from './app/controllers/crm-contact.controller';
import { CrmContactEntity } from './app/entities/crm-contact.entity';
import { CrmContactHooks } from './app/hooks/crm-contact.hooks';
import { CrmModuleLifecycleService } from './app/lifecycle/crm-module.lifecycle';
import { CrmContactPolicy } from './app/policies/crm-contact.policy';
import { CrmContactRepository } from './app/repositories/crm-contact.repository';
import { CrmContactSeeder } from './database/seeders/crm-contact.seeder';
import { CrmContactService } from './app/services/crm-contact.service';

export const crmConfig = registerAs('crm', () => crmAppConfig);

@Module({
  imports: [
    ConfigModule.forFeature(crmConfig),
    TypeOrmModule.forFeature([CrmContactEntity]),
  ],
  controllers: [CrmContactController],
  providers: [
    CrmContactService,
    CrmContactRepository,
    CrmContactPolicy,
    CrmContactHooks,
    CrmContactSeeder,
    CrmModuleLifecycleService,
  ],
  exports: [CrmContactService, CrmModuleLifecycleService],
})
export class CrmModule {}
