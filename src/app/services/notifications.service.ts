import { Injectable, Logger } from '@nestjs/common';
import {
  NotificationEvent,
  NotificationServicePort,
} from '../interfaces/notification.interface';

@Injectable()
export class NotificationsService implements NotificationServicePort {
  private readonly logger = new Logger(NotificationsService.name);

  async record(event: NotificationEvent): Promise<void> {
    this.logger.log(
      `notification event=${event.name} receivedAt=${event.receivedAt.toISOString()} payload=${JSON.stringify(event.payload)}`,
    );
  }
}
