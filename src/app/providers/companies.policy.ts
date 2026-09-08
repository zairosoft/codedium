import { ForbiddenException, Inject, Injectable } from '@nestjs/common';
import { RequestActor } from '@/app/helpers/request-actor';
import {
  PERMISSION_SERVICE,
  PermissionServicePort,
} from '@/app/interfaces/permission.interface';

@Injectable()
export class CompaniesPolicy {
  constructor(
    @Inject(PERMISSION_SERVICE)
    private readonly permissions: PermissionServicePort,
  ) {}

  assertCanRead(actor: RequestActor): void {
    this.assertPermission(actor, 'platform.company.read', 'Company access is forbidden.');
  }

  assertCanWrite(actor: RequestActor): void {
    this.assertPermission(actor, 'platform.company.write', 'Company changes are forbidden.');
  }

  private assertPermission(actor: RequestActor, permission: string, message: string): void {
    if (!this.permissions.hasPermissions(actor.roleCodes, [permission])) {
      throw new ForbiddenException(message);
    }
  }
}
