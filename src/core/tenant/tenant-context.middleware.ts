import { BadRequestException, Injectable, NestMiddleware } from '@nestjs/common';
import { Request, Response, NextFunction } from 'express';
import { normalizeTenantId } from './tenant.constants';
import { TenantContextService } from './tenant-context.service';

@Injectable()
export class TenantContextMiddleware implements NestMiddleware {
  constructor(private readonly tenantContext: TenantContextService) {}

  use(req: Request, _res: Response, next: NextFunction): void {
    const rawTenantId = req.headers['x-tenant-id'];
    const tenantIdHeader = Array.isArray(rawTenantId) ? rawTenantId[0] : rawTenantId;
    let tenantId: string;

    try {
      tenantId = normalizeTenantId(tenantIdHeader);
    } catch (error) {
      throw new BadRequestException((error as Error).message);
    }

    this.tenantContext.run(tenantId, next);
  }
}
