import { Module } from '@nestjs/common';
import { ROLE_SERVICE } from '../../core/interfaces/role.interface';
import { RolesService } from './services/roles.service';

@Module({
  providers: [
    RolesService,
    {
      provide: ROLE_SERVICE,
      useExisting: RolesService,
    },
  ],
  exports: [RolesService, ROLE_SERVICE],
})
export class RolesPlatformModule {}
