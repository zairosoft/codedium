import {
  Entity,
  Index,
  Column,
} from 'typeorm';
import { TenantScopedEntity } from '../../../core/tenant/tenant-scoped.entity';

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
}
