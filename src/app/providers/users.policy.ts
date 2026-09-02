import { ForbiddenException, Inject, Injectable } from '@nestjs/common';
import {
  PERMISSION_SERVICE,
  PermissionServicePort,
} from '@/app/interfaces/permission.interface';
import { UserRecord } from '@/app/interfaces/user.interface';
import { RequestActor } from '@/app/helpers/request-actor';

@Injectable()
export class UsersPolicy {
  private static readonly READ_PERMISSION = 'platform.user.read';
  private static readonly WRITE_PERMISSION = 'platform.user.write';
  private static readonly MEMBERSHIP_PERMISSION = 'platform.membership.assign';

  constructor(
    @Inject(PERMISSION_SERVICE)
    private readonly permissions: PermissionServicePort,
  ) {}

  assertCanReadDirectory(actor: RequestActor): void {
    this.assertPermissions(actor, [UsersPolicy.READ_PERMISSION], 'User directory access is forbidden.');
  }

  assertCanCreate(actor: RequestActor): void {
    this.assertPermissions(actor, [UsersPolicy.WRITE_PERMISSION], 'User creation is forbidden.');
  }

  assertCanUpdate(actor: RequestActor, target: UserRecord): void {
    if (actor.userId && actor.userId === target.id) {
      this.assertPermissions(
        actor,
        [UsersPolicy.READ_PERMISSION],
        'Self-service user updates require read access.',
      );
      return;
    }

    this.assertPermissions(actor, [UsersPolicy.WRITE_PERMISSION], 'User updates are forbidden.');
  }

  assertCanAssignMemberships(actor: RequestActor): void {
    this.assertPermissions(
      actor,
      [UsersPolicy.MEMBERSHIP_PERMISSION],
      'Membership assignment is forbidden.',
    );
  }

  private assertPermissions(
    actor: RequestActor,
    requiredPermissions: string[],
    message: string,
  ): void {
    if (!this.permissions.hasPermissions(actor.roleCodes, requiredPermissions)) {
      throw new ForbiddenException(message);
    }
  }
}
