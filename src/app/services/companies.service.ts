import { ConflictException, Inject, Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { ILike, Repository } from 'typeorm';
import { ListCompaniesDto } from '@/app/dto/list-companies.dto';
import { CompanyEntity } from '@/app/entities/company.entity';
import { PlatformUserEntity } from '@/app/entities/user.entity';
import { RequestActor } from '@/app/helpers/request-actor';
import {
  CompanyRecord,
  CreateCompanyInput,
  UpdateCompanyInput,
} from '@/app/interfaces/company.interface';
import { CompaniesPolicy } from '@/app/providers/companies.policy';
import { CACHE_PORT, CachePort } from '@/workless/infrastructure/cache/cache.interface';

type CachedCompanyRecord = Omit<CompanyRecord, 'createdAt' | 'updatedAt'> & {
  createdAt: string;
  updatedAt: string;
};

@Injectable()
export class CompaniesService {
  constructor(
    @InjectRepository(CompanyEntity)
    private readonly companiesRepository: Repository<CompanyEntity>,
    @InjectRepository(PlatformUserEntity)
    private readonly usersRepository: Repository<PlatformUserEntity>,
    private readonly companiesPolicy: CompaniesPolicy,
    @Inject(CACHE_PORT)
    private readonly cache: CachePort,
  ) {}

  async listCompanies(query: ListCompaniesDto, actor: RequestActor) {
    this.companiesPolicy.assertCanRead(actor);

    const page = query.page ?? 1;
    const limit = Math.min(query.limit ?? 20, 100);
    const search = query.search?.trim();
    const where = search
      ? [{ name: ILike(`%${search}%`) }, { code: ILike(`%${search}%`) }]
      : {};
    const cacheKey = `platform:companies:list:${page}:${limit}:${encodeURIComponent(
      search?.toLowerCase() ?? '',
    )}`;
    const result = await this.cache.remember<{
      data: CachedCompanyRecord[];
      meta: { page: number; limit: number; total: number };
    }>(cacheKey, 60, async () => {
      const [companies, total] = await this.companiesRepository.findAndCount({
        where,
        order: { createdAt: 'DESC' },
        skip: (page - 1) * limit,
        take: limit,
      });

      return {
        data: companies.map((company) => this.toCachedRecord(this.toRecord(company))),
        meta: { page, limit, total },
      };
    });

    return {
      data: result.data.map((company) => this.fromCachedRecord(company)),
      meta: result.meta,
    };
  }

  async getCompanyById(id: string, actor: RequestActor): Promise<CompanyRecord> {
    this.companiesPolicy.assertCanRead(actor);
    const company = await this.cache.remember<CachedCompanyRecord>(
      this.companyCacheKey(id),
      300,
      async () => this.toCachedRecord(this.toRecord(await this.getEntityOrFail(id))),
    );
    return this.fromCachedRecord(company);
  }

  async createCompany(input: CreateCompanyInput, actor: RequestActor): Promise<CompanyRecord> {
    this.companiesPolicy.assertCanWrite(actor);

    const code = this.normalizeCode(input.code);
    await this.ensureCodeIsAvailable(code);

    const company = this.companiesRepository.create({
      name: input.name.trim(),
      code,
      description: this.normalizeOptionalText(input.description),
      logo: this.normalizeOptionalText(input.logo),
      isActive: input.isActive ?? true,
      createdBy: actor.userId ?? null,
      updatedBy: actor.userId ?? null,
    });

    const created = this.toRecord(await this.companiesRepository.save(company));
    await this.invalidateCompanyCache(created.id);
    await this.cache.set(this.companyCacheKey(created.id), this.toCachedRecord(created), 300);
    return created;
  }

  async updateCompany(
    id: string,
    input: UpdateCompanyInput,
    actor: RequestActor,
  ): Promise<CompanyRecord> {
    this.companiesPolicy.assertCanWrite(actor);

    const company = await this.getEntityOrFail(id);
    if (company.id === '00000000-0000-0000-0000-000000000000' && input.isActive === false) {
      throw new ConflictException('The system company cannot be disabled.');
    }

    if (input.code !== undefined) {
      const code = this.normalizeCode(input.code);
      if (code !== company.code) {
        await this.ensureCodeIsAvailable(code, company.id);
        company.code = code;
      }
    }

    if (input.name !== undefined) company.name = input.name.trim();
    if (input.description !== undefined) {
      company.description = this.normalizeOptionalText(input.description);
    }
    if (input.logo !== undefined) company.logo = this.normalizeOptionalText(input.logo);
    if (input.isActive !== undefined) company.isActive = input.isActive;
    company.updatedBy = actor.userId ?? null;

    const updated = this.toRecord(await this.companiesRepository.save(company));
    await this.invalidateCompanyCache(updated.id);
    await this.cache.set(this.companyCacheKey(updated.id), this.toCachedRecord(updated), 300);
    return updated;
  }

  async deleteCompany(id: string, actor: RequestActor): Promise<void> {
    this.companiesPolicy.assertCanWrite(actor);

    const company = await this.getEntityOrFail(id);
    if (company.id === '00000000-0000-0000-0000-000000000000') {
      throw new ConflictException('The system company cannot be deleted.');
    }

    const assignedUsers = await this.usersRepository.count({ where: { companyId: id } });
    if (assignedUsers > 0) {
      throw new ConflictException(
        `Company "${company.name}" cannot be deleted while it has assigned users.`,
      );
    }

    company.deletedBy = actor.userId ?? null;
    await this.companiesRepository.save(company);
    await this.companiesRepository.softRemove(company);
    await this.invalidateCompanyCache(id);
  }

  private async getEntityOrFail(id: string): Promise<CompanyEntity> {
    const company = await this.companiesRepository.findOne({ where: { id } });
    if (!company) {
      throw new NotFoundException(`Company "${id}" was not found.`);
    }

    return company;
  }

  private async ensureCodeIsAvailable(code: string, exceptId?: string): Promise<void> {
    const existing = await this.companiesRepository.findOne({
      where: { code },
      withDeleted: true,
    });
    if (existing && existing.id !== exceptId) {
      throw new ConflictException(`Company code "${code}" already exists.`);
    }
  }

  private normalizeCode(code: string): string {
    return code.trim().toLowerCase();
  }

  private normalizeOptionalText(value?: string): string | null {
    const normalized = value?.trim();
    return normalized || null;
  }

  private companyCacheKey(id: string): string {
    return `platform:companies:${id}`;
  }

  private async invalidateCompanyCache(id: string): Promise<void> {
    await Promise.all([
      this.cache.del(this.companyCacheKey(id)),
      this.cache.delByPrefix('platform:companies:list:'),
    ]);
  }

  private toCachedRecord(company: CompanyRecord): CachedCompanyRecord {
    return {
      ...company,
      createdAt: company.createdAt.toISOString(),
      updatedAt: company.updatedAt.toISOString(),
    };
  }

  private fromCachedRecord(company: CachedCompanyRecord): CompanyRecord {
    return {
      ...company,
      createdAt: new Date(company.createdAt),
      updatedAt: new Date(company.updatedAt),
    };
  }

  private toRecord(company: CompanyEntity): CompanyRecord {
    return {
      id: company.id,
      name: company.name,
      code: company.code,
      description: company.description,
      logo: company.logo,
      isActive: company.isActive,
      createdAt: company.createdAt,
      updatedAt: company.updatedAt,
    };
  }
}
