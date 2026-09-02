import { Module } from '@nestjs/common';
import { ConfigModule, registerAs } from '@nestjs/config';
import { TypeOrmModule } from '@nestjs/typeorm';
import crmAppConfig from '@/modules/crm/app.config.json';
import { CrmContactController } from '@/modules/crm/app/controllers/crm-contact.controller';
import { CrmContactEntity } from '@/modules/crm/app/entities/crm-contact.entity';
import { CrmContactHooks } from '@/modules/crm/app/hooks/crm-contact.hooks';
import { CrmModuleLifecycleService } from '@/modules/crm/app/lifecycle/crm-module.lifecycle';
import { CrmContactPolicy } from '@/modules/crm/app/policies/crm-contact.policy';
import { CrmContactRepository } from '@/modules/crm/app/repositories/crm-contact.repository';
import { CrmContactSeeder } from '@/modules/crm/database/seeders/crm-contact.seeder';
import { CrmContactService } from '@/modules/crm/app/services/crm-contact.service';

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
