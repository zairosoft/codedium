import { Injectable } from '@nestjs/common';
import { Hook } from '@/workless/events/hook.decorator';
import { CreateContactDto } from '@/modules/crm/app/dto/create-contact.dto';
import { UpdateContactDto } from '@/modules/crm/app/dto/update-contact.dto';

@Injectable()
export class CrmContactHooks {
  @Hook('crm.contact.creating')
  beforeCreate(payload: CreateContactDto): CreateContactDto {
    return {
      ...payload,
      fullName: payload.fullName.trim(),
      email: payload.email.trim().toLowerCase(),
      phone: payload.phone?.trim() || undefined,
    };
  }

  @Hook('crm.contact.updating')
  beforeUpdate(payload: UpdateContactDto): UpdateContactDto {
    return {
      ...payload,
      fullName: payload.fullName?.trim(),
      email: payload.email?.trim().toLowerCase(),
      phone: payload.phone?.trim() || undefined,
    };
  }
}
