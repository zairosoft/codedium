import { randomUUID } from 'node:crypto';
import { Injectable, NotFoundException } from '@nestjs/common';
import { CreateUserInput, UpdateUserInput, UserServicePort } from '../../interfaces/user.interface';
import { UserModel } from '../models/user.model';

@Injectable()
export class UsersService implements UserServicePort {
  private readonly users = new Map<string, UserModel>();

  async findById(id: string): Promise<UserModel | null> {
    return this.users.get(id) ?? null;
  }

  async createUser(input: CreateUserInput): Promise<UserModel> {
    const now = new Date();
    const user: UserModel = {
      id: randomUUID(),
      email: input.email.trim().toLowerCase(),
      displayName: input.displayName.trim(),
      active: true,
      roles: [...(input.roles ?? [])],
      createdAt: now,
      updatedAt: now,
    };

    this.users.set(user.id, user);
    return user;
  }

  async updateUser(id: string, input: UpdateUserInput): Promise<UserModel> {
    const existing = this.users.get(id);
    if (!existing) {
      throw new NotFoundException(`User "${id}" was not found.`);
    }

    const updated: UserModel = {
      ...existing,
      email: input.email?.trim().toLowerCase() ?? existing.email,
      displayName: input.displayName?.trim() ?? existing.displayName,
      active: input.active ?? existing.active,
      roles: input.roles ?? existing.roles,
      updatedAt: new Date(),
    };

    this.users.set(id, updated);
    return updated;
  }
}
