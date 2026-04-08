import { Module } from '@nestjs/common';
import { NotificationsModuleLifecycleService } from './lifecycle/notifications-module.lifecycle';
import { NotificationsListener } from './services/notifications.listener';

@Module({
  providers: [NotificationsListener, NotificationsModuleLifecycleService],
})
export class NotificationsModule {}

