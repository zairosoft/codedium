import { IsEmail, IsEnum, IsOptional, IsString, IsUUID, MaxLength } from 'class-validator';
import { ContactStatus } from '../entities/crm-contact.entity';

export class UpdateContactDto {
  @IsOptional()
  @IsUUID()
  orgId?: string;

  @IsOptional()
  @IsString()
  @MaxLength(120)
  fullName?: string;

  @IsOptional()
  @IsEmail()
  email?: string;

  @IsOptional()
  @IsString()
  @MaxLength(30)
  phone?: string;

  @IsOptional()
  @IsEnum(ContactStatus)
  status?: ContactStatus;
}
