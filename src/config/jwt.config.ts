import { ConfigService } from '@nestjs/config';

export function resolveJwtSecret(configService: ConfigService): string {
  const secret = configService.get<string>('JWT_SECRET')?.trim();
  if (secret) {
    return secret;
  }

  throw new Error(
    'JWT_SECRET must not be empty. Set a secure value in .env or the process environment.',
  );
}
