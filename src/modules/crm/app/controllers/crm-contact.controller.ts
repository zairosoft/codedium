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
  Req,
  Res,
} from '@nestjs/common';
import type { Request, Response } from 'express';
import { RequirePermissions } from '@app/providers/require-permissions.decorator';
import { HtmlCacheable } from '@/workless/http/html-cache.decorator';
import { resolveLocaleFromRequest } from '@/workless/i18n';
import { RequiresModule } from '@/workless/module/module-enabled.decorator';
import { CreateContactDto } from '@/modules/crm/app/dto/create-contact.dto';
import { ListContactsDto } from '@/modules/crm/app/dto/list-contacts.dto';
import { UpdateContactDto } from '@/modules/crm/app/dto/update-contact.dto';
import { CrmContactService } from '@/modules/crm/app/services/crm-contact.service';
import { renderCrmDashboardPage } from '@/modules/crm/app/views/crm-dashboard.page';
import { CrmContactViewMapper } from '@/modules/crm/app/views/crm-contact.view';

@RequiresModule('crm')
@Controller('crm')
export class CrmContactController {
  constructor(private readonly crmContactService: CrmContactService) {}

  @Post('contacts')
  @RequirePermissions('crm.contact.write')
  async create(@Body() dto: CreateContactDto) {
    return this.crmContactService.createContact(dto);
  }

  @Get('contacts')
  @RequirePermissions('crm.contact.read')
  async findAll(@Query() query: ListContactsDto) {
    return this.crmContactService.getContacts(query);
  }

  @Get('contacts/:id')
  @RequirePermissions('crm.contact.read')
  async findOne(@Param('id') id: string) {
    return this.crmContactService.getContactById(id);
  }

  @Patch('contacts/:id')
  @RequirePermissions('crm.contact.write')
  async update(@Param('id') id: string, @Body() dto: UpdateContactDto) {
    return this.crmContactService.updateContact(id, dto);
  }

  @Delete('contacts/:id')
  @RequirePermissions('crm.contact.write')
  async remove(@Param('id') id: string) {
    await this.crmContactService.removeContact(id);
    return {
      type: 'result',
      resource: 'crm.contact',
      success: true,
    };
  }

  @Get('dashboard')
  @RequirePermissions('crm.contact.read')
  async getDashboard() {
    return this.crmContactService.getDashboardSummary();
  }

  @Get('dashboard/page')
  @RequirePermissions('crm.contact.read')
  @Header('Content-Type', 'text/html; charset=utf-8')
  @HtmlCacheable({
    maxAgeSeconds: 60,
    scope: 'public',
    vary: ['Accept-Encoding', 'X-Tenant-Id'],
    surrogateKey: 'crm-dashboard',
  })
  async renderDashboard(
    @Req() request: Request,
    @Res({ passthrough: true }) response: Response,
  ) {
    response.type('html');
    const summary = await this.crmContactService.getDashboardSummary();
    const locale = resolveLocaleFromRequest(request);
    return renderCrmDashboardPage(CrmContactViewMapper.toDashboard(summary), locale);
  }
}
