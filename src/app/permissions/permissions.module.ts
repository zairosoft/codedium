import { Module } from '@nestjs/common';
import { PERMISSION_SERVICE } from '../../core/interfaces/permission.interface';
import { PermissionGuard } from './guards/permission.guard';
import { PermissionsService } from './services/permissions.service';

@Module({
  providers: [
    PermissionsService,
    PermissionGuard,
    {
      provide: PERMISSION_SERVICE,
      useExisting: PermissionsService,
    },
  ],
  exports: [PermissionsService, PermissionGuard, PERMISSION_SERVICE],
})
export class PermissionsPlatformModule {}
