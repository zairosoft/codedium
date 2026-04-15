import { IsEmail, IsOptional, IsUUID } from 'class-validator';

export class LoginDto {
  @IsEmail()
  email: string;

  @IsOptional()
  @IsUUID()
  tenantId?: string;
}
