import { IsEmail, IsOptional, IsString, IsUUID, MaxLength } from 'class-validator';

export class LoginDto {
  @IsEmail()
  email: string;

  @IsString()
  @MaxLength(128)
  password: string;

  @IsOptional()
  @IsUUID()
  tenantId?: string;
}
