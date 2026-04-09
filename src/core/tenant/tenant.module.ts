import { Global, Module } from '@nestjs/common';
import { TENANT_CONTEXT } from './tenant-context.interface';
import { TenantContextService } from './tenant-context.service';

@Global()
@Module({
  providers: [
    TenantContextService,
    {
      provide: TENANT_CONTEXT,
      useExisting: TenantContextService,
    },
  ],
  exports: [TenantContextService, TENANT_CONTEXT],
})
export class TenantModule {}
