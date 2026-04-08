import {
  CallHandler,
  ExecutionContext,
  Injectable,
  NestInterceptor,
} from '@nestjs/common';
import { Reflector } from '@nestjs/core';
import { Observable } from 'rxjs';
import { HTML_CACHE_METADATA, HtmlCacheOptions } from './html-cache.decorator';

@Injectable()
export class HtmlCacheInterceptor implements NestInterceptor {
  constructor(private readonly reflector: Reflector) {}

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
    const scope = options.scope ?? 'public';

    response.setHeader('Cache-Control', `${scope}, max-age=${options.maxAgeSeconds}`);
    response.setHeader('Vary', (options.vary ?? ['Accept-Encoding']).join(', '));

    if (options.surrogateKey) {
      response.setHeader('Surrogate-Key', options.surrogateKey);
    }

    return next.handle();
  }
}

