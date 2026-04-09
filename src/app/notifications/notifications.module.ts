import { Module } from '@nestjs/common';
import { NOTIFICATION_SERVICE } from '../../core/interfaces/notification.interface';
import { NotificationsListener } from './services/notifications.listener';
import { NotificationsService } from './services/notifications.service';

@Module({
  providers: [
    NotificationsService,
    NotificationsListener,
    {
      provide: NOTIFICATION_SERVICE,
      useExisting: NotificationsService,
    },
  ],
  exports: [NotificationsService, NOTIFICATION_SERVICE],
})
export class NotificationsPlatformModule {}
