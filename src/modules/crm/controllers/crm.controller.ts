import {
  Body,
  Controller,
  Delete,
  Get,
  Param,
  Patch,
  Post,
  Query,
} from '@nestjs/common';
import { CreateContactDto } from '../dto/create-contact.dto';
import { ListContactsDto } from '../dto/list-contacts.dto';
import { UpdateContactDto } from '../dto/update-contact.dto';
import { CrmService } from '../services/crm.service';

@Controller('crm/contacts')
export class CrmController {
  constructor(private readonly crmService: CrmService) {}

  @Post()
  create(@Body() dto: CreateContactDto) {
    return this.crmService.createContact(dto);
  }

  @Get()
  findAll(@Query() query: ListContactsDto) {
    return this.crmService.getContacts(query);
  }

  @Get(':id')
  findOne(@Param('id') id: string) {
    return this.crmService.getContactById(id);
  }

  @Patch(':id')
  update(@Param('id') id: string, @Body() dto: UpdateContactDto) {
    return this.crmService.updateContact(id, dto);
  }

  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.crmService.removeContact(id);
  }
}

