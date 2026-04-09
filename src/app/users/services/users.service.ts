import { randomUUID } from 'node:crypto';
import {
  ConflictException,
  Injectable,
  Logger,
  NotFoundException,
  OnApplicationBootstrap,
} from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { CreateUserInput, UpdateUserInput, UserServicePort } from '../../interfaces/user.interface';
import { PlatformUserEntity } from '../entities/platform-user.entity';
import { UserModel } from '../models/user.model';
import { DataSource, Repository, Table, TableIndex } from 'typeorm';

@Injectable()
export class UsersService implements UserServicePort, OnApplicationBootstrap {
  private readonly logger = new Logger(UsersService.name);

  constructor(
    @InjectRepository(PlatformUserEntity)
    private readonly usersRepository: Repository<PlatformUserEntity>,
    private readonly dataSource: DataSource,
  ) {}

  async onApplicationBootstrap(): Promise<void> {
    await this.ensureUsersTable();
  }

  async findById(id: string): Promise<UserModel | null> {
    const entity = await this.usersRepository.findOne({
      where: { id },
    });
    return entity ? this.toModel(entity) : null;
  }

  async createUser(input: CreateUserInput): Promise<UserModel> {
    const email = input.email.trim().toLowerCase();
    const existing = await this.usersRepository.findOne({
      where: { email },
    });

    if (existing) {
      throw new ConflictException(`User email "${email}" already exists.`);
    }

    const now = new Date();
    const user = this.usersRepository.create({
      id: randomUUID(),
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
    const existing = await this.usersRepository.findOne({
      where: { id },
    });

    if (!existing) {
      throw new NotFoundException(`User "${id}" was not found.`);
    }

    const nextEmail = input.email?.trim().toLowerCase();
    if (nextEmail && nextEmail !== existing.email) {
      const duplicate = await this.usersRepository.findOne({
        where: { email: nextEmail },
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

  private async ensureUsersTable(): Promise<void> {
    const queryRunner = this.dataSource.createQueryRunner();
    await queryRunner.connect();

    try {
      const hasTable = await queryRunner.hasTable('platform_users');
      if (!hasTable) {
        await queryRunner.createTable(
          new Table({
            name: 'platform_users',
            columns: [
              {
                name: 'id',
                type: 'uuid',
                isPrimary: true,
                isNullable: false,
              },
              {
                name: 'email',
                type: 'varchar',
                length: '160',
                isNullable: false,
              },
              {
                name: 'displayName',
                type: 'varchar',
                length: '120',
                isNullable: false,
              },
              {
                name: 'active',
                type: 'boolean',
                default: true,
                isNullable: false,
              },
              {
                name: 'roles',
                type: 'jsonb',
                default: "'[]'::jsonb",
                isNullable: false,
              },
              {
                name: 'createdAt',
                type: 'timestamptz',
                default: 'now()',
                isNullable: false,
              },
              {
                name: 'updatedAt',
                type: 'timestamptz',
                default: 'now()',
                isNullable: false,
              },
            ],
          }),
          true,
        );
        this.logger.log('Created platform_users table for platform IAM persistence.');
      }

      const hasEmailIndex = await queryRunner.hasIndex('platform_users', 'uq_platform_users_email');
      if (!hasEmailIndex) {
        await queryRunner.createIndex(
          'platform_users',
          new TableIndex({
            name: 'uq_platform_users_email',
            columnNames: ['email'],
            isUnique: true,
          }),
        );
      }
    } finally {
      await queryRunner.release();
    }
  }
}
