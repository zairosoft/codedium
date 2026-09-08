import { Injectable, NestMiddleware } from '@nestjs/common';
import type { NextFunction, Request, Response } from 'express';
import { RequestCompanyContextService } from '@/app/providers/request-company-context.service';

@Injectable()
export class CompanyContextMiddleware implements NestMiddleware {
  constructor(private readonly companyContext: RequestCompanyContextService) {}

  use(_request: Request, _response: Response, next: NextFunction): void {
    this.companyContext.run(next);
  }
}
