import { Inject, Injectable, UnauthorizedException } from '@nestjs/common';
import { AuthServicePort } from '../../../core/interfaces/auth.interface';
import { USER_SERVICE, UserServicePort } from '../../../core/interfaces/user.interface';
import { AuthSessionModel } from '../models/auth-session.model';

@Injectable()
export class AuthService implements AuthServicePort {
  constructor(@Inject(USER_SERVICE) private readonly users: UserServicePort) {}

  async createSession(userId: string, tenantId?: string): Promise<AuthSessionModel> {
    const user = await this.users.findById(userId);
    if (!user || !user.active) {
      throw new UnauthorizedException('User is not available for authentication.');
    }

    return {
      userId: user.id,
      tenantId,
      authenticatedAt: new Date(),
    };
  }
}
