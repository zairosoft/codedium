import {
  Injectable,
  Logger,
  NotFoundException,
  OnApplicationBootstrap,
} from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { SystemModuleExplorer } from '../system/system-module.explorer';
import { ModuleRegistryEntity, ModuleStatus } from './module-registry.entity';

@Injectable()
export class ModuleRegistryService implements OnApplicationBootstrap {
  private readonly logger = new Logger(ModuleRegistryService.name);

  constructor(
    @InjectRepository(ModuleRegistryEntity)
    private readonly moduleRegistryRepository: Repository<ModuleRegistryEntity>,
    private readonly moduleExplorer: SystemModuleExplorer,
  ) {}

  async onApplicationBootstrap(): Promise<void> {
    await this.syncWithCodebase();
  }

  async syncWithCodebase(): Promise<ModuleRegistryEntity[]> {
    const discoveredModules = this.moduleExplorer.getModules();
    const syncedRecords: ModuleRegistryEntity[] = [];

    for (const definition of discoveredModules) {
      const existing = await this.moduleRegistryRepository.findOne({
        where: { name: definition.metadata.name },
      });

      if (existing) {
        existing.version = definition.metadata.version;
        existing.description = definition.metadata.description ?? null;
        existing.dependencies = definition.metadata.dependencies;
        syncedRecords.push(await this.moduleRegistryRepository.save(existing));
        continue;
      }

      const created = this.moduleRegistryRepository.create({
        name: definition.metadata.name,
        version: definition.metadata.version,
        description: definition.metadata.description ?? null,
        dependencies: definition.metadata.dependencies,
        status: ModuleStatus.UNINSTALLED,
        enabled: false,
      });

      syncedRecords.push(await this.moduleRegistryRepository.save(created));
      this.logger.log(`Registered System module definition "${created.name}"`);
    }

    return syncedRecords;
  }

  async list(): Promise<ModuleRegistryEntity[]> {
    await this.syncWithCodebase();
    return this.moduleRegistryRepository.find({
      order: { name: 'ASC' },
    });
  }

  async getOrFail(name: string): Promise<ModuleRegistryEntity> {
    await this.syncWithCodebase();
    const record = await this.moduleRegistryRepository.findOne({
      where: { name },
    });

    if (!record) {
      throw new NotFoundException(`System module "${name}" is not registered.`);
    }

    return record;
  }

  async isEnabled(name: string): Promise<boolean> {
    const record = await this.getOrFail(name);
    return record.enabled && record.status === ModuleStatus.INSTALLED;
  }

  async markInstalled(name: string, version: string): Promise<ModuleRegistryEntity> {
    const record = await this.getOrFail(name);
    record.version = version;
    record.status = ModuleStatus.INSTALLED;
    record.enabled = true;
    record.installedAt = record.installedAt ?? new Date();
    record.upgradedAt = new Date();
    return this.moduleRegistryRepository.save(record);
  }

  async markDisabled(name: string): Promise<ModuleRegistryEntity> {
    const record = await this.getOrFail(name);
    record.status = ModuleStatus.DISABLED;
    record.enabled = false;
    return this.moduleRegistryRepository.save(record);
  }

  async markUninstalled(name: string): Promise<ModuleRegistryEntity> {
    const record = await this.getOrFail(name);
    record.status = ModuleStatus.UNINSTALLED;
    record.enabled = false;
    return this.moduleRegistryRepository.save(record);
  }
}

