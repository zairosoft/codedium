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
}

