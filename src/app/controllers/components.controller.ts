import { Controller, Get, Header, Res } from '@nestjs/common';
import type { Response } from 'express';
import { renderComponentShowcasePage } from '@/app/views/component-showcase/component-showcase.page';
import { Public } from '@/workless/jwt/public.decorator';

@Controller('components')
export class ComponentsController {
  @Public()
  @Get()
  @Header('Content-Type', 'text/html; charset=utf-8')
  renderComponents(@Res({ passthrough: true }) response: Response) {
    response.type('html');
    return renderComponentShowcasePage();
  }
}
