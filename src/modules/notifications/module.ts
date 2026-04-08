import { Module } from '@nestjs/common';
import { NotificationsListener } from './services/notifications.listener';

@Module({
  providers: [NotificationsListener],
})
export class NotificationsModule {}


