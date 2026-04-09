import { Inject, Injectable } from '@nestjs/common';
import { OnEvent } from '@nestjs/event-emitter';
import { EVENT_BUS_PORT, EventBusPort } from '../../../core/interfaces/event-bus.interface';
import { NotificationsService } from './notifications.service';

@Injectable()
export class NotificationsListener {
  constructor(
    private readonly notifications: NotificationsService,
    @Inject(EVENT_BUS_PORT) private readonly eventBus: EventBusPort,
  ) {}

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
    await this.eventBus.emit('notification.sent', {
      name: payload.name,
      payload: payload.payload,
      sentAt: payload.receivedAt ?? new Date().toISOString(),
    });
  }
}
