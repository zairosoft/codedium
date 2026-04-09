import { randomUUID } from 'node:crypto';
import {
  ConflictException,
  Inject,
  Injectable,
  NotFoundException,
} from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import {
  CreateUserInput,
  UpdateUserInput,
  UserServicePort,
} from '../../../core/interfaces/user.interface';
import { TENANT_CONTEXT, TenantContextPort } from '../../../core/tenant/tenant-context.interface';
import { PlatformUserEntity } from '../entities/platform-user.entity';
import { UserModel } from '../models/user.model';
import { Repository } from 'typeorm';

@Injectable()
export class UsersService implements UserServicePort {
  constructor(
    @InjectRepository(PlatformUserEntity)
    private readonly usersRepository: Repository<PlatformUserEntity>,
    @Inject(TENANT_CONTEXT)
    private readonly tenantContext: TenantContextPort,
  ) {}

  async findById(id: string): Promise<UserModel | null> {
    const tenantId = this.tenantContext.getTenantId();
    const entity = await this.usersRepository.findOne({
      where: { id, tenantId },
    });
    return entity ? this.toModel(entity) : null;
  }

  async createUser(input: CreateUserInput): Promise<UserModel> {
    const tenantId = this.tenantContext.getTenantId();
    const email = input.email.trim().toLowerCase();
    const existing = await this.usersRepository.findOne({
      where: { tenantId, email },
    });

    if (existing) {
      throw new ConflictException(`User email "${email}" already exists.`);
    }

    const now = new Date();
    const user = this.usersRepository.create({
      id: randomUUID(),
      tenantId,
      email,
      displayName: input.displayName.trim(),
      active: true,
      roles: [...(input.roles ?? [])],
      createdAt: now,
      updatedAt: now,
    });

    return this.toModel(await this.usersRepository.save(user));
  }

  async updateUser(id: string, input: UpdateUserInput): Promise<UserModel> {
    const tenantId = this.tenantContext.getTenantId();
    const existing = await this.usersRepository.findOne({
      where: { id, tenantId },
    });

    if (!existing) {
      throw new NotFoundException(`User "${id}" was not found.`);
    }

    const nextEmail = input.email?.trim().toLowerCase();
    if (nextEmail && nextEmail !== existing.email) {
      const duplicate = await this.usersRepository.findOne({
        where: { tenantId, email: nextEmail },
      });

      if (duplicate) {
        throw new ConflictException(`User email "${nextEmail}" already exists.`);
      }
    }

    const updated = this.usersRepository.create({
      ...existing,
      email: nextEmail ?? existing.email,
      displayName: input.displayName?.trim() ?? existing.displayName,
      active: input.active ?? existing.active,
      roles: input.roles ?? existing.roles,
      updatedAt: new Date(),
    });

    return this.toModel(await this.usersRepository.save(updated));
  }

  private toModel(entity: PlatformUserEntity): UserModel {
    return {
      id: entity.id,
      email: entity.email,
      displayName: entity.displayName,
      active: entity.active,
      roles: [...(entity.roles ?? [])],
      createdAt: entity.createdAt,
      updatedAt: entity.updatedAt,
    };
  }
}
