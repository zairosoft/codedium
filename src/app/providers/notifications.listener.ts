import { Inject, Injectable } from '@nestjs/common';
import { OnEvent } from '@nestjs/event-emitter';
import {
  NOTIFICATION_SERVICE,
  NotificationEvent,
  NotificationServicePort,
} from '../../core/interfaces/notification.interface';

@Injectable()
export class NotificationsListener {
  constructor(
    @Inject(NOTIFICATION_SERVICE)
    private readonly notifications: NotificationServicePort,
  ) {}

  @OnEvent('user.created')
  async handleUserCreated(payload: Record<string, unknown>): Promise<void> {
    await this.notifications.record(this.toEvent('user.created', payload));
  }

  @OnEvent('user.updated')
  async handleUserUpdated(payload: Record<string, unknown>): Promise<void> {
    await this.notifications.record(this.toEvent('user.updated', payload));
  }

  private toEvent(name: string, payload: Record<string, unknown>): NotificationEvent {
    return {
      name,
      payload,
      receivedAt: new Date(),
    };
  }
}
