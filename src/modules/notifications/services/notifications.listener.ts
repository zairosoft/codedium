import { Injectable, Logger } from '@nestjs/common';
import { OnEvent } from '@nestjs/event-emitter';

@Injectable()
export class NotificationsListener {
  private readonly logger = new Logger(NotificationsListener.name);

  @OnEvent('customer.afterCreate')
  async handleCrmContactCreated(payload: {
    contactId: string;
    tenantId: string;
    orgId: string;
    email: string;
  }): Promise<void> {
    this.logger.log(
      `customer.afterCreate received: tenant=${payload.tenantId} contact=${payload.contactId} org=${payload.orgId} email=${payload.email}`,
    );
  }

  @OnEvent('customer.afterUpdate')
  async handleCrmContactUpdated(payload: {
    contactId: string;
    tenantId: string;
    orgId: string;
    email: string;
  }): Promise<void> {
    this.logger.log(
      `customer.afterUpdate received: tenant=${payload.tenantId} contact=${payload.contactId} org=${payload.orgId} email=${payload.email}`,
    );
  }

  @OnEvent('customer.afterDelete')
  async handleCrmContactDeleted(payload: {
    contactId: string;
    tenantId: string;
  }): Promise<void> {
    this.logger.log(
      `customer.afterDelete received: tenant=${payload.tenantId} contact=${payload.contactId}`,
    );
  }

  @OnEvent('system.module.installed')
  async handleModuleInstalled(payload: {
    name: string;
    version: string;
  }): Promise<void> {
    this.logger.log(`system.module.installed received: module=${payload.name}@${payload.version}`);
  }
}
