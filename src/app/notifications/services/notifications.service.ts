import { Injectable, Logger } from '@nestjs/common';
import { NotificationEventModel } from '../models/notification-event.model';

@Injectable()
export class NotificationsService {
  private readonly logger = new Logger(NotificationsService.name);

  async record(event: NotificationEventModel): Promise<void> {
    this.logger.log(
      `notification event=${event.name} receivedAt=${event.receivedAt.toISOString()} payload=${JSON.stringify(event.payload)}`,
    );
  }
}
