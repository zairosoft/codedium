import { Module } from '@nestjs/common';
import { PERMISSION_SERVICE } from '../../core/interfaces/permission.interface';
import { PermissionsService } from './services/permissions.service';

@Module({
  providers: [
    PermissionsService,
    {
      provide: PERMISSION_SERVICE,
      useExisting: PermissionsService,
    },
  ],
  exports: [PermissionsService, PERMISSION_SERVICE],
})
export class PermissionsPlatformModule {}
