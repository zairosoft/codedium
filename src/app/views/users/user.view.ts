import { MembershipRecord, UserRecord } from '../../../core/interfaces/user.interface';

export type UserItemView = {
  id: string;
  email: string;
  displayName: string;
  active: boolean;
  platformRoles: string[];
  organizations: {
    organizationId: string;
    roleCode: string;
    isDefault: boolean;
  }[];
  createdAt: string;
};

export class UsersViewMapper {
  static toItem(user: UserRecord): UserItemView {
    return {
      id: user.id,
      email: user.email,
      displayName: user.displayName,
      active: user.active,
      platformRoles: [...user.roles],
      organizations: user.memberships.map((membership) => this.toMembership(membership)),
      createdAt: user.createdAt.toISOString(),
    };
  }

  static toTableSchema(users: UserRecord[], meta: { page: number; limit: number; total: number }) {
    return {
      type: 'table',
      resource: 'platform.user',
      columns: [
        { key: 'displayName', label: 'Display Name' },
        { key: 'email', label: 'Email' },
        { key: 'active', label: 'Active' },
        { key: 'platformRoles', label: 'Platform Roles' },
        { key: 'organizations', label: 'Organization Memberships' },
      ],
      data: users.map((user) => this.toItem(user)),
      meta,
    };
  }

  static toDetailSchema(user: UserRecord) {
    return {
      type: 'detail',
      resource: 'platform.user',
      data: this.toItem(user),
      sections: [
        {
          key: 'identity',
          title: 'Identity',
          fields: ['displayName', 'email', 'active'],
        },
        {
          key: 'access',
          title: 'Access',
          fields: ['platformRoles', 'organizations'],
        },
      ],
    };
  }

  private static toMembership(membership: MembershipRecord) {
    return {
      organizationId: membership.organizationId,
      roleCode: membership.roleCode,
      isDefault: membership.isDefault ?? false,
    };
  }
}
