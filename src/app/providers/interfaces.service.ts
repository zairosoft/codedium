import { Provider } from '@nestjs/common';
import { AUTH_SERVICE } from '@/app/interfaces/auth.interface';
import { NOTIFICATION_SERVICE } from '@/app/interfaces/notification.interface';
import { PERMISSION_SERVICE } from '@/app/interfaces/permission.interface';
import { ROLE_SERVICE } from '@/app/interfaces/role.interface';
import { USER_SERVICE } from '@/app/interfaces/user.interface';
import { AuthService } from '@/app/services/auth.service';
import { NotificationsService } from '@/app/services/notifications.service';
import { PermissionsService } from '@/app/providers/permissions.service';
import { RolesService } from '@/app/services/roles.service';
import { UsersService } from '@/app/services/users.service';

/**
 * NestJS modules remain responsible for loading controllers and dependencies;
 * this file centralizes application-service registrations and port bindings.
 */
export const platformServiceProviders: Provider[] = [
  AuthService,
  NotificationsService,
  PermissionsService,
  RolesService,
  UsersService,
  { provide: AUTH_SERVICE, useExisting: AuthService },
  { provide: NOTIFICATION_SERVICE, useExisting: NotificationsService },
  { provide: PERMISSION_SERVICE, useExisting: PermissionsService },
  { provide: ROLE_SERVICE, useExisting: RolesService },
  { provide: USER_SERVICE, useExisting: UsersService },
];

export const platformServiceExports = [
  AuthService,
  NotificationsService,
  PermissionsService,
  RolesService,
  UsersService,
  AUTH_SERVICE,
  NOTIFICATION_SERVICE,
  PERMISSION_SERVICE,
  ROLE_SERVICE,
  USER_SERVICE,
];
