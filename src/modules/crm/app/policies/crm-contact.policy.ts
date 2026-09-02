import { ForbiddenException, Injectable } from '@nestjs/common';
import { ListContactsDto } from '../dto/list-contacts.dto';
import { ContactStatus } from '../entities/crm-contact.entity';
import { CrmActor } from './crm-actor.policy';

@Injectable()
export class CrmContactPolicy {
  private static readonly READ_PERMISSION = 'crm.contact.read';
  private static readonly WRITE_PERMISSION = 'crm.contact.write';

  normalizeListQuery(query: ListContactsDto): ListContactsDto {
    return {
      page: query.page ?? 1,
      limit: Math.min(query.limit ?? 20, 100),
      search: query.search?.trim() || undefined,
    };
  }

  assertCanRead(actor: CrmActor): void {
    this.assertPermissions(actor, [CrmContactPolicy.READ_PERMISSION], 'CRM contact read access is forbidden.');
  }

  assertCanWrite(actor: CrmActor): void {
    this.assertPermissions(actor, [CrmContactPolicy.WRITE_PERMISSION], 'CRM contact write access is forbidden.');
  }

  assertCanChangeStatus(currentStatus: ContactStatus, nextStatus?: ContactStatus): void {
    if (!nextStatus || nextStatus === currentStatus) {
      return;
    }

    if (currentStatus === ContactStatus.CUSTOMER && nextStatus === ContactStatus.LEAD) {
      throw new ForbiddenException('Customer contacts cannot be downgraded back to lead.');
    }
  }

  private assertPermissions(actor: CrmActor, requiredPermissions: string[], message: string): void {
    const permissionSet = new Set(actor.permissionCodes);
    if (!requiredPermissions.every((permission) => permissionSet.has(permission))) {
      throw new ForbiddenException(message);
    }
  }
}

