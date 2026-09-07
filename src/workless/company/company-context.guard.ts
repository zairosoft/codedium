import { CanActivate, ExecutionContext, Injectable } from '@nestjs/common';
import type { Request } from 'express';
import type { AuthenticatedUser } from '@/app/interfaces/auth.interface';
import { CompanyContextService } from '@/workless/company/company-context.service';

type AuthenticatedRequest = Request & { user?: AuthenticatedUser };

@Injectable()
export class CompanyContextGuard implements CanActivate {
  constructor(private readonly companyContext: CompanyContextService) {}

  canActivate(context: ExecutionContext): boolean {
    if (context.getType() !== 'http') {
      return true;
    }

    const request = context.switchToHttp().getRequest<AuthenticatedRequest>();
    if (request.user) {
      this.companyContext.activate(request.user.companyId);
    }

    return true;
  }
}
