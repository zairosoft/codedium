import { Injectable } from '@nestjs/common';
import { RoleRecord, RoleServicePort } from '@/app/interfaces/role.interface';

@Injectable()
export class RolesService implements RoleServicePort {
  private readonly roles: RoleRecord[] = [
    { code: 'platform.admin', description: 'Platform administrator' },
    { code: 'platform.manager', description: 'Operational manager' },
    { code: 'org.admin', description: 'Organization administrator' },
    { code: 'org.member', description: 'Organization member' },
    { code: 'admin', description: 'Legacy alias for platform administrator' },
    { code: 'manager', description: 'Legacy alias for platform manager' },
    { code: 'user', description: 'Legacy alias for organization member' },
  ];

  list(): RoleRecord[] {
    return [...this.roles];
  }
}
