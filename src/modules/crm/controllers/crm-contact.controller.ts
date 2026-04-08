import {
  Body,
  Controller,
  Delete,
  Get,
  Header,
  Param,
  Patch,
  Post,
  Query,
  Res,
} from '@nestjs/common';
import { Response } from 'express';
import { HtmlCacheable } from '../../../core/http/html-cache.decorator';
import { RequiresModule } from '../../../core/system/module-enabled.decorator';
import { CreateContactDto } from '../dto/create-contact.dto';
import { ListContactsDto } from '../dto/list-contacts.dto';
import { UpdateContactDto } from '../dto/update-contact.dto';
import { CrmContactService } from '../services/crm-contact.service';
import { renderCrmDashboardPage } from '../views/crm-dashboard.page';

@RequiresModule('crm')
@Controller('crm')
export class CrmContactController {
  constructor(private readonly crmContactService: CrmContactService) {}

  @Post('contacts')
  create(@Body() dto: CreateContactDto) {
    return this.crmContactService.createContact(dto);
  }

  @Get('contacts')
  findAll(@Query() query: ListContactsDto) {
    return this.crmContactService.getContacts(query);
  }

  @Get('contacts/:id')
  findOne(@Param('id') id: string) {
    return this.crmContactService.getContactById(id);
  }

  @Patch('contacts/:id')
  update(@Param('id') id: string, @Body() dto: UpdateContactDto) {
    return this.crmContactService.updateContact(id, dto);
  }

  @Delete('contacts/:id')
  remove(@Param('id') id: string) {
    return this.crmContactService.removeContact(id);
  }

  @Get('dashboard')
  getDashboard() {
    return this.crmContactService.getDashboardSummary();
  }

  @Get('dashboard/page')
  @Header('Content-Type', 'text/html; charset=utf-8')
  @HtmlCacheable({
    maxAgeSeconds: 60,
    scope: 'public',
    vary: ['Accept-Encoding', 'X-Tenant-Id'],
    surrogateKey: 'crm-dashboard',
  })
  async renderDashboard(@Res({ passthrough: true }) response: Response) {
    response.type('html');
    const summary = await this.crmContactService.getDashboardSummary();
    return renderCrmDashboardPage(summary);
  }
}
