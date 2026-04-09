import { Injectable } from '@nestjs/common';
import { OnEvent } from '@nestjs/event-emitter';
import { NotificationsService } from './notifications.service';

@Injectable()
export class NotificationsListener {
  constructor(private readonly notifications: NotificationsService) {}

  @OnEvent('notification.send')
  async handleNotificationSend(payload: {
    name: string;
    payload: Record<string, unknown>;
    receivedAt?: string;
  }): Promise<void> {
    await this.notifications.record({
      name: payload.name,
      payload: payload.payload,
      receivedAt: payload.receivedAt ? new Date(payload.receivedAt) : new Date(),
    });
  }
}
