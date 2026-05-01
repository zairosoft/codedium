import { Column, Entity, Index, OneToMany } from 'typeorm';
import { TenantScopedEntity } from '../../workless/tenant/tenant-scoped.entity';
import { PlatformMembershipEntity } from './platform-membership.entity';

@Entity({ name: 'platform_users' })
@Index(['tenantId', 'email'], { unique: true })
export class PlatformUserEntity extends TenantScopedEntity {
  @Column({ type: 'varchar', length: 160 })
  email: string;

  @Column({ type: 'varchar', length: 120 })
  displayName: string;

  @Column({ type: 'boolean', default: true })
  active: boolean;

  @Column({ type: 'jsonb', default: () => "'[]'::jsonb" })
  roles: string[];

  @OneToMany(() => PlatformMembershipEntity, (membership) => membership.user)
  memberships?: PlatformMembershipEntity[];
}
