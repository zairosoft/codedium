import {
  IsArray,
  IsBoolean,
  IsEmail,
  IsOptional,
  IsString,
  IsUUID,
  MaxLength,
  ValidateNested,
} from 'class-validator';
import { Type } from 'class-transformer';

class CreateMembershipDto {
  @IsUUID()
  organizationId: string;

  @IsString()
  @MaxLength(80)
  roleCode: string;

  @IsOptional()
  @IsBoolean()
  isDefault?: boolean;
}

export class CreateUserDto {
  @IsEmail()
  email: string;

  @IsString()
  @MaxLength(120)
  displayName: string;

  @IsOptional()
  @IsArray()
  @IsString({ each: true })
  roles?: string[];

  @IsOptional()
  @IsArray()
  @ValidateNested({ each: true })
  @Type(() => CreateMembershipDto)
  memberships?: CreateMembershipDto[];
}
