import { Controller, Get, Header, Res } from '@nestjs/common';
import { Response } from 'express';
import { renderHomePage } from '../views/home/home.page';

@Controller()
export class HomeController {
  @Get()
  @Header('Content-Type', 'text/html; charset=utf-8')
  renderRoot(@Res({ passthrough: true }) response: Response) {
    response.type('html');
    return renderHomePage();
  }
}
