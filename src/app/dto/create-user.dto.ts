import {
  IsArray,
  IsEmail,
  IsOptional,
  IsString,
  MaxLength,
} from 'class-validator';

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
}
