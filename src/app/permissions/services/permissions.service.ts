import { Injectable } from '@nestjs/common';
import { PermissionServicePort } from '../../../core/interfaces/permission.interface';
import { PermissionModel } from '../models/permission.model';

@Injectable()
export class PermissionsService implements PermissionServicePort {
  private readonly grants = new Map<string, PermissionModel[]>([
    [
      'admin',
      [
        { resource: 'system.modules', actions: ['install', 'uninstall', 'upgrade'] },
        { resource: 'system.users', actions: ['create', 'read', 'update', 'delete'] },
        { resource: 'system.roles', actions: ['read'] },
        { resource: 'system.permissions', actions: ['read'] },
      ],
    ],
    [
      'manager',
      [
        { resource: 'system.users', actions: ['read', 'update'] },
        { resource: 'system.permissions', actions: ['read'] },
      ],
    ],
  ]);

  listForRole(roleCode: string): PermissionModel[] {
    return [...(this.grants.get(roleCode) ?? [])];
  }
}
