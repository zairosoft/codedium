import { Inject, Injectable, UnauthorizedException } from '@nestjs/common';
import { USER_SERVICE, UserServicePort } from '../../interfaces/user.interface';
import { AuthSessionModel } from '../models/auth-session.model';

@Injectable()
export class AuthService {
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
