import { Module } from '@nestjs/common';
import { RolesService } from './services/roles.service';

@Module({
  providers: [RolesService],
  exports: [RolesService],
})
export class RolesPlatformModule {}
