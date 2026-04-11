import {
  CanActivate,
  ExecutionContext,
  ForbiddenException,
  Inject,
  Injectable,
  UnauthorizedException,
} from '@nestjs/common';
import { Reflector } from '@nestjs/core';
import {
  PERMISSION_SERVICE,
  PermissionServicePort,
} from '../../../core/interfaces/permission.interface';
import { PermissionAwareRequest, resolveRequestActor } from '../request-actor';
import { REQUIRED_PERMISSIONS_METADATA } from './require-permissions.decorator';

@Injectable()
export class PermissionGuard implements CanActivate {
  constructor(
    private readonly reflector: Reflector,
    @Inject(PERMISSION_SERVICE)
    private readonly permissions: PermissionServicePort,
  ) {}

  canActivate(context: ExecutionContext): boolean {
    if (context.getType() !== 'http') {
      return true;
    }

    const request = context.switchToHttp().getRequest<PermissionAwareRequest>();
    const actor = resolveRequestActor(request);
    request.actor = actor;

    const requiredPermissions =
      this.reflector.getAllAndOverride<string[]>(REQUIRED_PERMISSIONS_METADATA, [
        context.getHandler(),
        context.getClass(),
      ]) ?? [];

    if (requiredPermissions.length === 0) {
      return true;
    }

    if (actor.roleCodes.length === 0) {
      throw new UnauthorizedException('Authenticated actor roles are required.');
    }

    if (!this.permissions.hasPermissions(actor.roleCodes, requiredPermissions)) {
      throw new ForbiddenException(
        `Missing required permissions: ${requiredPermissions.join(', ')}`,
      );
    }

    return true;
  }
}
