import { Injectable } from '@nestjs/common';
import { PermissionModel } from '../models/permission.model';

@Injectable()
export class PermissionsService {
  private readonly grants = new Map<string, PermissionModel[]>([
    [
      'admin',
      [
        { resource: 'system.modules', actions: ['install', 'uninstall', 'upgrade'] },
        { resource: 'crm.contacts', actions: ['create', 'read', 'update', 'delete'] },
      ],
    ],
    [
      'manager',
      [{ resource: 'crm.contacts', actions: ['create', 'read', 'update'] }],
    ],
  ]);

  listForRole(roleCode: string): PermissionModel[] {
    return [...(this.grants.get(roleCode) ?? [])];
  }
}
