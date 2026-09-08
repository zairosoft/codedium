import { IsBoolean, IsOptional, IsString, Matches, MaxLength } from 'class-validator';

export class UpdateCompanyDto {
  @IsOptional()
  @IsString()
  @MaxLength(160)
  name?: string;

  @IsOptional()
  @IsString()
  @MaxLength(80)
  @Matches(/^[a-z0-9][a-z0-9_-]*$/, {
    message: 'code must contain only lowercase letters, numbers, underscores, or hyphens',
  })
  code?: string;

  @IsOptional()
  @IsString()
  description?: string;

  @IsOptional()
  @IsString()
  @MaxLength(500)
  logo?: string;

  @IsOptional()
  @IsBoolean()
  isActive?: boolean;
}
