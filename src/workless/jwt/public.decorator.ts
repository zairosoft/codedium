import { SetMetadata } from '@nestjs/common';

export const IS_PUBLIC_KEY = 'isPublic';

/**
 * Mark a route as public — bypasses JWT authentication.
 * Use sparingly and only for endpoints that genuinely require no auth
 * (e.g. login, health checks, public landing pages).
 */
export const Public = () => SetMetadata(IS_PUBLIC_KEY, true);
