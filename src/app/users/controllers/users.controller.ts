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
import { PermissionAwareRequest } from '../../permissions/models/request-actor.model';
import { PermissionGuard } from '../../permissions/guards/permission.guard';
import { RequirePermissions } from '../../permissions/guards/require-permissions.decorator';
import { UsersService } from '../services/users.service';
import { UsersViewMapper } from '../views/user.view';
import { CreateUserDto } from './create-user.dto';
import { ListUsersDto } from './list-users.dto';
import { UpdateUserDto } from './update-user.dto';

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
