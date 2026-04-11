import { Controller, Get, Header, Res } from '@nestjs/common';
import { Response } from 'express';
import { renderLandingPage } from '../views/landing/landing.page';

@Controller()
export class LandingController {
  @Get()
  @Header('Content-Type', 'text/html; charset=utf-8')
  renderRoot(@Res({ passthrough: true }) response: Response) {
    response.type('html');
    return renderLandingPage();
  }
}
