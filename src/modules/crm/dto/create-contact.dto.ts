import { IsEmail, IsEnum, IsOptional, IsString, IsUUID, MaxLength } from 'class-validator';
import { ContactStatus } from '../entities/crm-contact.entity';

export class CreateContactDto {
  @IsUUID()
  orgId: string;

  @IsString()
  @MaxLength(120)
  fullName: string;

  @IsEmail()
  email: string;

  @IsOptional()
  @IsString()
  @MaxLength(30)
  phone?: string;

  @IsOptional()
  @IsEnum(ContactStatus)
  status?: ContactStatus;
}

