import { randomUUID } from 'node:crypto';
import { ConflictException, Inject, Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import {
  CreateUserInput,
  MembershipRecord,
  UpdateUserInput,
  UserRecord,
  UserServicePort,
} from '../interfaces/user.interface';
import { EVENT_BUS_PORT, EventBusPort } from '../interfaces/event-bus.interface';
import { HOOK_PORT, HookPort } from '../interfaces/hook.interface';
import { TENANT_CONTEXT, TenantContextPort } from '../../workless/tenant/tenant-context.interface';
import { RequestActor } from '../helpers/request-actor';
import { ListUsersDto } from '../dto/list-users.dto';
import { PlatformMembershipEntity } from '../entities/platform-membership.entity';
import { PlatformUserEntity } from '../entities/platform-user.entity';
import { UsersPolicy } from '../providers/users.policy';
import { DataSource, ILike, Repository } from 'typeorm';

@Injectable()
export class UsersService implements UserServicePort {
  constructor(
    @InjectRepository(PlatformUserEntity)
    private readonly usersRepository: Repository<PlatformUserEntity>,
    private readonly dataSource: DataSource,
    private readonly usersPolicy: UsersPolicy,
    @Inject(EVENT_BUS_PORT)
    private readonly eventBus: EventBusPort,
    @Inject(HOOK_PORT)
    private readonly hookService: HookPort,
    @Inject(TENANT_CONTEXT)
    private readonly tenantContext: TenantContextPort,
  ) {}

  async findById(id: string): Promise<UserRecord | null> {
    const tenantId = this.tenantContext.getTenantId();
    const entity = await this.usersRepository.findOne({
      where: { id, companyId: tenantId },
      relations: { memberships: true },
    });
    return entity ? this.toRecord(entity) : null;
  }

  async findByEmail(email: string): Promise<UserRecord | null> {
    const tenantId = this.tenantContext.getTenantId();
    const entity = await this.usersRepository.findOne({
      where: { companyId: tenantId, email: email.trim().toLowerCase() },
      relations: { memberships: true },
    });
    return entity ? this.toRecord(entity) : null;
  }

  async getUserById(id: string, actor?: RequestActor): Promise<UserRecord> {
    if (actor) {
      this.usersPolicy.assertCanReadDirectory(actor);
    }

    const user = await this.findById(id);
    if (!user) {
      throw new NotFoundException(`User "${id}" was not found.`);
    }

    return user;
  }

  async listUsers(query: ListUsersDto, actor?: RequestActor) {
    if (actor) {
      this.usersPolicy.assertCanReadDirectory(actor);
    }

    const tenantId = this.tenantContext.getTenantId();
    const page = query.page ?? 1;
    const limit = Math.min(query.limit ?? 20, 100);
    const search = query.search?.trim();
    const where = search
      ? [
          { companyId: tenantId, email: ILike(`%${search}%`) },
          { companyId: tenantId, displayName: ILike(`%${search}%`) },
        ]
      : { companyId: tenantId };

    const [entities, total] = await this.usersRepository.findAndCount({
      where,
      relations: { memberships: true },
      order: { createdAt: 'DESC' },
      skip: (page - 1) * limit,
      take: limit,
    });

    return {
      data: entities.map((entity) => this.toRecord(entity)),
      meta: {
        page,
        limit,
        total,
      },
    };
  }

  async createUser(input: CreateUserInput, actor?: RequestActor): Promise<UserRecord> {
    if (actor) {
      this.usersPolicy.assertCanCreate(actor);
      if ((input.memberships?.length ?? 0) > 0) {
        this.usersPolicy.assertCanAssignMemberships(actor);
      }
    }

    const payload = await this.hookService.emit('user.creating', input);
    const tenantId = this.tenantContext.getTenantId();
    const email = payload.email.trim().toLowerCase();

    const created = await this.dataSource.transaction(async (manager) => {
      const usersRepository = manager.getRepository(PlatformUserEntity);
      const membershipsRepository = manager.getRepository(PlatformMembershipEntity);
      const existing = await usersRepository.findOne({
        where: { companyId: tenantId, email },
      });

      if (existing) {
        throw new ConflictException(`User email "${email}" already exists.`);
      }

      const now = new Date();
      const user = usersRepository.create({
        id: randomUUID(),
        companyId: tenantId,
        email,
        displayName: payload.displayName.trim(),
        active: true,
        roles: [...(payload.roles ?? [])],
        createdAt: now,
        updatedAt: now,
      });

      await usersRepository.save(user);
      await this.replaceMemberships(
        membershipsRepository,
        tenantId,
        user.id,
        payload.memberships ?? [],
      );

      const persisted = await usersRepository.findOne({
        where: { id: user.id, companyId: tenantId },
        relations: { memberships: true },
      });

      if (!persisted) {
        throw new NotFoundException(`User "${user.id}" was not found after creation.`);
      }

      return this.toRecord(persisted);
    });

    await this.eventBus.emit('user.created', {
      userId: created.id,
      tenantId,
      email: created.email,
      memberships: created.memberships.length,
    });

    return created;
  }

  async updateUser(id: string, input: UpdateUserInput, actor?: RequestActor): Promise<UserRecord> {
    const tenantId = this.tenantContext.getTenantId();
    const payload = await this.hookService.emit('user.updating', input);
    const existing = await this.usersRepository.findOne({
      where: { id, companyId: tenantId },
      relations: { memberships: true },
    });

    if (!existing) {
      throw new NotFoundException(`User "${id}" was not found.`);
    }

    const current = this.toRecord(existing);
    if (actor) {
      this.usersPolicy.assertCanUpdate(actor, current);
      if (payload.memberships !== undefined) {
        this.usersPolicy.assertCanAssignMemberships(actor);
      }
    }

    const nextEmail = payload.email?.trim().toLowerCase();
    if (nextEmail && nextEmail !== existing.email) {
      const duplicate = await this.usersRepository.findOne({
        where: { companyId: tenantId, email: nextEmail },
      });

      if (duplicate) {
        throw new ConflictException(`User email "${nextEmail}" already exists.`);
      }
    }

    const updated = await this.dataSource.transaction(async (manager) => {
      const usersRepository = manager.getRepository(PlatformUserEntity);
      const membershipsRepository = manager.getRepository(PlatformMembershipEntity);
      const nextState = usersRepository.create({
        ...existing,
        email: nextEmail ?? existing.email,
        displayName: payload.displayName?.trim() ?? existing.displayName,
        active: payload.active ?? existing.active,
        roles: payload.roles ?? existing.roles,
        updatedAt: new Date(),
      });

      await usersRepository.save(nextState);
      if (payload.memberships !== undefined) {
        await this.replaceMemberships(
          membershipsRepository,
          tenantId,
          existing.id,
          payload.memberships,
        );
      }

      const persisted = await usersRepository.findOne({
        where: { id: existing.id, companyId: tenantId },
        relations: { memberships: true },
      });

      if (!persisted) {
        throw new NotFoundException(`User "${existing.id}" was not found after update.`);
      }

      return this.toRecord(persisted);
    });

    await this.eventBus.emit('user.updated', {
      userId: updated.id,
      tenantId,
      email: updated.email,
      memberships: updated.memberships.length,
    });

    return updated;
  }

  private toRecord(entity: PlatformUserEntity): UserRecord {
    return {
      id: entity.id,
      companyId: entity.companyId,
      email: entity.email,
      displayName: entity.displayName,
      active: entity.active,
      roles: [...(entity.roles ?? [])],
      memberships: (entity.memberships ?? []).map((membership) => ({
        companyId: membership.companyId,
        organizationId: membership.organizationId,
        roleCode: membership.roleCode,
        isDefault: membership.isDefault,
      })),
      createdAt: entity.createdAt,
      updatedAt: entity.updatedAt,
    };
  }

  private async replaceMemberships(
    membershipsRepository: Repository<PlatformMembershipEntity>,
    tenantId: string,
    userId: string,
    memberships: MembershipRecord[],
  ): Promise<void> {
    await membershipsRepository.softDelete({ tenantId, userId });

    if (memberships.length === 0) {
      return;
    }

    const normalizedMemberships = memberships.map((membership, index) =>
      membershipsRepository.create({
        id: randomUUID(),
        tenantId,
        userId,
        companyId: membership.companyId,
        organizationId: membership.organizationId,
        roleCode: membership.roleCode.trim(),
        isDefault: membership.isDefault ?? index === 0,
      }),
    );

    await membershipsRepository.save(normalizedMemberships);
  }
}
