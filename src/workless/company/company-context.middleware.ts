import { Injectable, NestMiddleware } from '@nestjs/common';
import type { NextFunction, Request, Response } from 'express';
import { DEFAULT_COMPANY_ID } from '@/workless/company/company.constants';
import { CompanyContextService } from '@/workless/company/company-context.service';

@Injectable()
export class CompanyContextMiddleware implements NestMiddleware {
  constructor(private readonly companyContext: CompanyContextService) {}

  use(_request: Request, _response: Response, next: NextFunction): void {
    // Authentication guards run after middleware. Initialize an isolated request
    // context here, then activate the database-backed company in CompanyContextGuard.
    this.companyContext.run(DEFAULT_COMPANY_ID, next);
  }
}
