import { Catch, ExceptionFilter, HttpException } from '@nestjs/common';
import type { ArgumentsHost } from '@nestjs/common';
import type { Request, Response } from 'express';
import { resolveLocaleFromRequest, type AppLocale } from '../i18n';
import { render401Page } from '../../app/views/errors/401.page';
import { render403Page } from '../../app/views/errors/403.page';
import { render404Page } from '../../app/views/errors/404.page';
import { render419Page } from '../../app/views/errors/419.page';
import { render429Page } from '../../app/views/errors/429.page';
import { render500Page } from '../../app/views/errors/500.page';
import { render503Page } from '../../app/views/errors/503.page';

type HttpErrorStatus = 401 | 403 | 404 | 419 | 429 | 500 | 503;

// Maps supported HTTP status codes to their dedicated server-rendered error pages.
// Keep each page separate so its design can evolve independently.
const errorViews: Record<HttpErrorStatus, (options?: { locale?: AppLocale }) => string> = {
  401: render401Page,
  403: render403Page,
  404: render404Page,
  419: render419Page,
  429: render429Page,
  500: render500Page,
  503: render503Page,
};

@Catch()
export class HttpErrorViewFilter implements ExceptionFilter {
  catch(exception: unknown, host: ArgumentsHost): void {
    const context = host.switchToHttp();
    const request = context.getRequest<Request>();
    const response = context.getResponse<Response>();
    const status = exception instanceof HttpException ? exception.getStatus() : 500;
    const requestPath = request.originalUrl ?? request.url;
    const acceptsHtml = request.headers.accept?.includes('text/html') ?? false;

    // Browser navigation receives an HTML error page. API clients continue to
    // receive JSON so existing integrations can handle errors programmatically.
    if (
      !response.headersSent &&
      !requestPath.startsWith('/api/') &&
      acceptsHtml &&
      status in errorViews
    ) {
      response
        .status(status)
        .type('html')
        .send(errorViews[status as HttpErrorStatus]({ locale: resolveLocaleFromRequest(request) }));
      return;
    }

    // Another handler has already started the response; writing again would fail.
    if (response.headersSent) {
      return;
    }

    // Preserve NestJS HTTP exception bodies for API clients.
    if (exception instanceof HttpException) {
      response.status(status).json(exception.getResponse());
      return;
    }

    // Do not expose unexpected exception details to clients.
    response.status(500).json({
      statusCode: 500,
      message: 'Internal server error',
    });
  }
}
