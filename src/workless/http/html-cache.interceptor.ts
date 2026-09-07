import {
  CallHandler,
  ExecutionContext,
  Inject,
  Injectable,
  NestInterceptor,
} from '@nestjs/common';
import { Reflector } from '@nestjs/core';
import { Observable } from 'rxjs';
import { HTML_CACHE_METADATA, HtmlCacheOptions } from '@/workless/http/html-cache.decorator';
import { MODULE_ENABLED_METADATA } from '@/workless/module/module-enabled.decorator';
import { COMPANY_CONTEXT, CompanyContextPort } from '@/workless/company/company-context.interface';

@Injectable()
export class HtmlCacheInterceptor implements NestInterceptor {
  constructor(
    private readonly reflector: Reflector,
    @Inject(COMPANY_CONTEXT)
    private readonly companyContext: CompanyContextPort,
  ) {}

  intercept(context: ExecutionContext, next: CallHandler): Observable<unknown> {
    if (context.getType() !== 'http') {
      return next.handle();
    }

    const options = this.reflector.getAllAndOverride<HtmlCacheOptions | undefined>(
      HTML_CACHE_METADATA,
      [context.getHandler(), context.getClass()],
    );

    if (!options) {
      return next.handle();
    }

    const response = context.switchToHttp().getResponse();
    const request = context.switchToHttp().getRequest();
    const scope = options.scope ?? 'public';
    const moduleName =
      this.reflector.getAllAndOverride<string | undefined>(MODULE_ENABLED_METADATA, [
        context.getHandler(),
        context.getClass(),
      ]) ?? 'platform';
    const route = request.originalUrl ?? request.url ?? 'unknown-route';
    const companyId = this.companyContext.getCompanyId();
    const cacheKey = `${moduleName}:${companyId}:${route}`;

    response.setHeader('Cache-Control', `${scope}, max-age=${options.maxAgeSeconds}`);
    response.setHeader('Vary', (options.vary ?? ['Accept-Encoding']).join(', '));

    // SECURITY: Only expose internal cache key in non-production environments
    if (process.env.NODE_ENV !== 'production') {
      response.setHeader('X-HTML-Cache-Key', cacheKey);
    }

    if (options.surrogateKey) {
      response.setHeader('Surrogate-Key', `${options.surrogateKey} ${cacheKey}`);
    } else {
      response.setHeader('Surrogate-Key', cacheKey);
    }

    return next.handle();
  }
}
