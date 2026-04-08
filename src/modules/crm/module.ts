import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { CrmContactController } from './controllers/crm-contact.controller';
import { CrmContactHooks } from './hooks/crm-contact.hooks';
import { CrmContactEntity } from './models/crm-contact.entity';
import { CrmContactPolicy } from './policies/crm-contact.policy';
import { CrmContactRepository } from './repositories/crm-contact.repository';
import { CrmContactSeeder } from './seeders/crm-contact.seeder';
import { CrmContactService } from './services/crm-contact.service';
import { CrmModuleLifecycleService } from './services/crm-module.lifecycle';

@Module({
  imports: [TypeOrmModule.forFeature([CrmContactEntity])],
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


