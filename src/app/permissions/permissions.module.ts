import { Module } from '@nestjs/common';
import { PermissionsService } from './services/permissions.service';

@Module({
  providers: [PermissionsService],
  exports: [PermissionsService],
})
export class PermissionsPlatformModule {}
