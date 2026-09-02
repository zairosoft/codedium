import {
  Body,
  Controller,
  Get,
  Param,
  Patch,
  Post,
  Query,
  Req,
  UseGuards,
} from '@nestjs/common';
import type { PermissionAwareRequest } from '@/app/helpers/request-actor';
import { PermissionGuard } from '@/app/providers/permission.guard';
import { RequirePermissions } from '@/app/providers/require-permissions.decorator';
import { UsersService } from '@/app/services/users.service';
import { UsersViewMapper } from '@/app/views/users/user.view';
import { CreateUserDto } from '@/app/dto/create-user.dto';
import { ListUsersDto } from '@/app/dto/list-users.dto';
import { UpdateUserDto } from '@/app/dto/update-user.dto';

@Controller('platform/users')
@UseGuards(PermissionGuard)
export class UsersController {
  constructor(private readonly usersService: UsersService) {}

  @Get()
  @RequirePermissions('platform.user.read')
  async list(@Query() query: ListUsersDto, @Req() request: PermissionAwareRequest) {
    const result = await this.usersService.listUsers(query, request.actor);
    return UsersViewMapper.toTableSchema(result.data, result.meta);
  }

  @Get(':id')
  @RequirePermissions('platform.user.read')
  async getOne(@Param('id') id: string, @Req() request: PermissionAwareRequest) {
    const user = await this.usersService.getUserById(id, request.actor);
    return UsersViewMapper.toDetailSchema(user);
  }

  @Post()
  @RequirePermissions('platform.user.write')
  async create(@Body() dto: CreateUserDto, @Req() request: PermissionAwareRequest) {
    const user = await this.usersService.createUser(dto, request.actor);
    return UsersViewMapper.toDetailSchema(user);
  }

  @Patch(':id')
  @RequirePermissions('platform.user.write')
  async update(
    @Param('id') id: string,
    @Body() dto: UpdateUserDto,
    @Req() request: PermissionAwareRequest,
  ) {
    const user = await this.usersService.updateUser(id, dto, request.actor);
    return UsersViewMapper.toDetailSchema(user);
  }
}
