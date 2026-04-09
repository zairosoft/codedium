import { Inject, Injectable } from '@nestjs/common';
import { OnEvent } from '@nestjs/event-emitter';
import { EVENT_BUS_PORT, EventBusPort } from '../../../core/interfaces/event-bus.interface';

@Injectable()
export class UsersEventsListener {
  constructor(@Inject(EVENT_BUS_PORT) private readonly eventBus: EventBusPort) {}

  @OnEvent('user.created')
  async handleUserCreated(payload: {
    userId: string;
    tenantId: string;
    email: string;
    memberships: number;
  }): Promise<void> {
    await this.eventBus.emit('notification.send', {
      name: 'user.created',
      payload,
      receivedAt: new Date().toISOString(),
    });
  }
}
