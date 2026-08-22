import { Controller, Get, Header, Res } from '@nestjs/common';
import type { Response } from 'express';
import { Public } from '../../workless/jwt/public.decorator';
import { renderHomePage } from '../views/home/home.page';

@Controller()
export class HomeController {
  @Public()
  @Get()
  @Header('Content-Type', 'text/html; charset=utf-8')
  renderRoot(@Res({ passthrough: true }) response: Response) {
    response.type('html');
    return renderHomePage();
  }
}
