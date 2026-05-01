import { Column, Entity, Index, JoinColumn, ManyToOne } from 'typeorm';
import { TenantScopedEntity } from '../../workless/tenant/tenant-scoped.entity';
import { PlatformUserEntity } from './platform-user.entity';

@Entity({ name: 'platform_user_memberships' })
@Index(['tenantId', 'userId', 'organizationId'], { unique: true })
export class PlatformMembershipEntity extends TenantScopedEntity {
  @Column({ type: 'uuid' })
  userId: string;

  @Column({ type: 'uuid' })
  organizationId: string;

  @Column({ type: 'varchar', length: 80 })
  roleCode: string;

  @Column({ type: 'boolean', default: false })
  isDefault: boolean;

  @ManyToOne(() => PlatformUserEntity, (user) => user.memberships, {
    onDelete: 'CASCADE',
  })
  @JoinColumn({ name: 'userId' })
  user: PlatformUserEntity;
}
