import { Injectable } from '@nestjs/common';
import { RoleServicePort } from '../../../core/interfaces/role.interface';
import { RoleModel } from '../models/role.model';

@Injectable()
export class RolesService implements RoleServicePort {
  private readonly roles: RoleModel[] = [
    { code: 'admin', description: 'Platform administrator' },
    { code: 'manager', description: 'Operational manager' },
    { code: 'user', description: 'Standard platform user' },
  ];

  list(): RoleModel[] {
    return [...this.roles];
  }
}
