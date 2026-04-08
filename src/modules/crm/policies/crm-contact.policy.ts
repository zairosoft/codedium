import { Injectable } from '@nestjs/common';
import { ListContactsDto } from '../dto/list-contacts.dto';

@Injectable()
export class CrmContactPolicy {
  normalizeListQuery(query: ListContactsDto): ListContactsDto {
    return {
      page: query.page ?? 1,
      limit: Math.min(query.limit ?? 20, 100),
      search: query.search?.trim() || undefined,
    };
  }
}

