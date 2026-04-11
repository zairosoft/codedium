import {
  Body,
  Controller,
  Delete,
  Get,
  Req,
  Header,
  Param,
  Patch,
  Post,
  Query,
  Res,
} from '@nestjs/common';
import { Request, Response } from 'express';
import { HtmlCacheable } from '../../../core/http/html-cache.decorator';
import { RequiresModule } from '../../../core/module/module-enabled.decorator';
import { CreateContactDto } from '../dto/create-contact.dto';
import { ListContactsDto } from '../dto/list-contacts.dto';
import { UpdateContactDto } from '../dto/update-contact.dto';
import { resolveCrmActor } from '../policies/crm-actor.policy';
import { CrmContactService } from '../services/crm-contact.service';
import { renderCrmDashboardPage } from '../views/crm-dashboard.page';
import { CrmContactViewMapper } from '../views/crm-contact.view';

@RequiresModule('crm')
@Controller('crm')
export class CrmContactController {
  constructor(private readonly crmContactService: CrmContactService) {}

  @Post('contacts')
  async create(@Body() dto: CreateContactDto, @Req() request: Request) {
    return this.crmContactService.createContact(dto, resolveCrmActor(request));
  }

  @Get('contacts')
  async findAll(@Query() query: ListContactsDto, @Req() request: Request) {
    return this.crmContactService.getContacts(query, resolveCrmActor(request));
  }

  @Get('contacts/:id')
  async findOne(@Param('id') id: string, @Req() request: Request) {
    return this.crmContactService.getContactById(id, resolveCrmActor(request));
  }

  @Patch('contacts/:id')
  async update(@Param('id') id: string, @Body() dto: UpdateContactDto, @Req() request: Request) {
    return this.crmContactService.updateContact(id, dto, resolveCrmActor(request));
  }

  @Delete('contacts/:id')
  async remove(@Param('id') id: string, @Req() request: Request) {
    await this.crmContactService.removeContact(id, resolveCrmActor(request));
    return {
      type: 'result',
      resource: 'crm.contact',
      success: true,
    };
  }

  @Get('dashboard')
  async getDashboard(@Req() request: Request) {
    return this.crmContactService.getDashboardSummary(resolveCrmActor(request));
  }

  @Get('dashboard/page')
  @Header('Content-Type', 'text/html; charset=utf-8')
  @HtmlCacheable({
    maxAgeSeconds: 60,
    scope: 'public',
    vary: ['Accept-Encoding', 'X-Tenant-Id'],
    surrogateKey: 'crm-dashboard',
  })
  async renderDashboard(@Req() request: Request, @Res({ passthrough: true }) response: Response) {
    response.type('html');
    const summary = await this.crmContactService.getDashboardSummary(resolveCrmActor(request));
    return renderCrmDashboardPage(CrmContactViewMapper.toDashboard(summary));
  }
}
