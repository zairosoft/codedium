import { Body, Controller, Get, Header, Post, Res } from '@nestjs/common';
import { Response } from 'express';
import { Public } from '../auth/public.decorator';
import { AuthService } from '../services/auth.service';
import { LoginDto } from '../dto/login.dto';
import { renderLoginPage } from '../views/auth/login.page';

@Controller('auth')
export class AuthController {
  constructor(private readonly authService: AuthService) {}

  /**
   * GET /auth/login — renders the HTML login page
   */
  @Public()
  @Get('login')
  @Header('Content-Type', 'text/html; charset=utf-8')
  renderLogin(@Res({ passthrough: true }) response: Response) {
    response.type('html');
    return renderLoginPage();
  }

  /**
   * POST /auth/login — JSON API endpoint that returns a JWT token
   */
  @Public()
  @Post('login')
  async login(@Body() dto: LoginDto) {
    return this.authService.login(dto.email, dto.tenantId);
  }
}
