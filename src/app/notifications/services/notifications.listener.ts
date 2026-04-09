import { Injectable } from '@nestjs/common';
import { OnEvent } from '@nestjs/event-emitter';
import { NotificationsService } from './notifications.service';

@Injectable()
export class NotificationsListener {
  constructor(private readonly notifications: NotificationsService) {}

  @OnEvent('customer.afterCreate')
  async handleCrmContactCreated(payload: {
    contactId: string;
    tenantId: string;
    orgId: string;
    email: string;
  }): Promise<void> {
    await this.notifications.record({
      name: 'customer.afterCreate',
      payload,
      receivedAt: new Date(),
    });
  }

  @OnEvent('customer.afterUpdate')
  async handleCrmContactUpdated(payload: {
    contactId: string;
    tenantId: string;
    orgId: string;
    email: string;
  }): Promise<void> {
    await this.notifications.record({
      name: 'customer.afterUpdate',
      payload,
      receivedAt: new Date(),
    });
  }

  @OnEvent('customer.afterDelete')
  async handleCrmContactDeleted(payload: {
    contactId: string;
    tenantId: string;
  }): Promise<void> {
    await this.notifications.record({
      name: 'customer.afterDelete',
      payload,
      receivedAt: new Date(),
    });
  }

  @OnEvent('system.module.installed')
  async handleModuleInstalled(payload: {
    name: string;
    version: string;
  }): Promise<void> {
    await this.notifications.record({
      name: 'system.module.installed',
      payload,
      receivedAt: new Date(),
    });
  }
}
