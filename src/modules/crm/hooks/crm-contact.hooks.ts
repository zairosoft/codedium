import { Injectable } from '@nestjs/common';
import { Hook } from '../../../core/events/hook.decorator';
import { CreateContactDto } from '../dto/create-contact.dto';
import { UpdateContactDto } from '../dto/update-contact.dto';

@Injectable()
export class CrmContactHooks {
  @Hook('customer.beforeCreate')
  beforeCreate(payload: CreateContactDto): CreateContactDto {
    return {
      ...payload,
      fullName: payload.fullName.trim(),
      email: payload.email.trim().toLowerCase(),
      phone: payload.phone?.trim() || undefined,
    };
  }

  @Hook('customer.beforeUpdate')
  beforeUpdate(payload: UpdateContactDto): UpdateContactDto {
    return {
      ...payload,
      fullName: payload.fullName?.trim(),
      email: payload.email?.trim().toLowerCase(),
      phone: payload.phone?.trim() || undefined,
    };
  }
}

