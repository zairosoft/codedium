import { Column, Entity, Index, JoinColumn, ManyToOne } from 'typeorm';
import { TenantScopedEntity } from '../../workless/tenant/tenant-scoped.entity';
import { PlatformUserEntity } from './user.entity';

@Entity({ name: 'user_memberships' })
@Index(['tenantId', 'userId', 'companyId', 'organizationId'], { unique: true })
@Index(['tenantId', 'companyId'])
export class PlatformMembershipEntity extends TenantScopedEntity {
  @Column({ type: 'uuid' })
  userId: string;

  @Column({ name: 'company_id', type: 'uuid' })
  companyId: string;

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
