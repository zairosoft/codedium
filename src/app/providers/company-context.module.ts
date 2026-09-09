import { Global, Module } from '@nestjs/common';
import { COMPANY_CONTEXT } from '@/app/interfaces/company-context.interface';
import { RequestCompanyContextService } from '@/app/providers/request-company-context.service';

@Global()
@Module({
  providers: [
    RequestCompanyContextService,
    { provide: COMPANY_CONTEXT, useExisting: RequestCompanyContextService },
  ],
  exports: [COMPANY_CONTEXT, RequestCompanyContextService],
})
export class CompanyContextModule {}
