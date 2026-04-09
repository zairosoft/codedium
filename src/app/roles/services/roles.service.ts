import { Injectable } from '@nestjs/common';
import { RoleServicePort } from '../../../core/interfaces/role.interface';
import { RoleModel } from '../models/role.model';

@Injectable()
export class RolesService implements RoleServicePort {
  private readonly roles: RoleModel[] = [
    { code: 'platform.admin', description: 'Platform administrator' },
    { code: 'platform.manager', description: 'Operational manager' },
    { code: 'org.admin', description: 'Organization administrator' },
    { code: 'org.member', description: 'Organization member' },
    { code: 'admin', description: 'Legacy alias for platform administrator' },
    { code: 'manager', description: 'Legacy alias for platform manager' },
    { code: 'user', description: 'Legacy alias for organization member' },
  ];

  list(): RoleModel[] {
    return [...this.roles];
  }
}
