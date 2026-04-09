import { Module } from '@nestjs/common';
import { NotificationsListener } from './services/notifications.listener';
import { NotificationsService } from './services/notifications.service';

@Module({
  providers: [NotificationsService, NotificationsListener],
  exports: [NotificationsService],
})
export class NotificationsPlatformModule {}
