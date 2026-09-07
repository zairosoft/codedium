import { Global, Module } from '@nestjs/common';
import { COMPANY_CONTEXT } from '@/workless/company/company-context.interface';
import { CompanyContextService } from '@/workless/company/company-context.service';

@Global()
@Module({
  providers: [
    CompanyContextService,
    {
      provide: COMPANY_CONTEXT,
      useExisting: CompanyContextService,
    },
  ],
  exports: [CompanyContextService, COMPANY_CONTEXT],
})
export class CompanyModule {}
