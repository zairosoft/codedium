import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { CrmContactController } from './controllers/crm-contact.controller';
import { CrmContactEntity } from './entities/crm-contact.entity';
import { CrmContactHooks } from './hooks/crm-contact.hooks';
import { CrmModuleLifecycleService } from './lifecycle/crm-module.lifecycle';
import { CrmContactPolicy } from './policies/crm-contact.policy';
import { CrmContactRepository } from './app/repositories/crm-contact.repository';
import { CrmContactSeeder } from './seeders/crm-contact.seeder';
import { CrmContactService } from './app/services/crm-contact.service';

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
