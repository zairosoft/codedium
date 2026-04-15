import { BadRequestException, ForbiddenException, Injectable, NestMiddleware } from '@nestjs/common';
import { Request, Response, NextFunction } from 'express';
import { normalizeTenantId } from './tenant.constants';
import { TenantContextService } from './tenant-context.service';
import type { AuthenticatedUser } from '../interfaces/auth.interface';

type AuthenticatedRequest = Request & { user?: AuthenticatedUser };

@Injectable()
export class TenantContextMiddleware implements NestMiddleware {
  constructor(private readonly tenantContext: TenantContextService) {}

  use(req: AuthenticatedRequest, _res: Response, next: NextFunction): void {
    const rawTenantId = req.headers['x-tenant-id'];
    const tenantIdHeader = Array.isArray(rawTenantId) ? rawTenantId[0] : rawTenantId;
    const authenticatedUser = req.user;

    let tenantId: string;

    try {
      // If authenticated user has a tenantId in JWT and no header is sent,
      // use the JWT tenantId. If header is sent, validate it.
      if (tenantIdHeader) {
        tenantId = normalizeTenantId(tenantIdHeader);
      } else if (authenticatedUser?.tenantId) {
        tenantId = normalizeTenantId(authenticatedUser.tenantId);
      } else {
        tenantId = normalizeTenantId(undefined);
      }
    } catch (error) {
      throw new BadRequestException((error as Error).message);
    }

    // If user is authenticated, verify they have access to the requested tenant
    if (authenticatedUser && authenticatedUser.tenantId) {
      const userTenantId = normalizeTenantId(authenticatedUser.tenantId);
      if (tenantId !== userTenantId) {
        throw new ForbiddenException(
          'Access denied: you do not have permission to access this tenant.',
        );
      }
    }

    this.tenantContext.run(tenantId, next);
  }
}
