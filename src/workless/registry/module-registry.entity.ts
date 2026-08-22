import {
  Column,
  CreateDateColumn,
  Entity,
  Index,
  PrimaryGeneratedColumn,
  UpdateDateColumn,
} from 'typeorm';

export enum ModuleStatus {
  INSTALLED = 'installed',
  UNINSTALLED = 'uninstalled',
  DISABLED = 'disabled',
}

@Entity({ name: 'module_registries' })
export class ModuleRegistryEntity {
  @PrimaryGeneratedColumn('uuid')
  id: string;

  @Index({ unique: true })
  @Column({ type: 'varchar', length: 80 })
  name: string;

  @Column({ type: 'varchar', length: 32 })
  version: string;

  @Column({ type: 'varchar', length: 32, nullable: true })
  availableVersion?: string | null;

  @Column({ type: 'varchar', length: 20, default: ModuleStatus.UNINSTALLED })
  status: ModuleStatus;

  @Column({ type: 'boolean', default: false })
  enabled: boolean;

  @Column({ type: 'varchar', length: 255, nullable: true })
  description?: string | null;

  @Column({ type: 'simple-array', nullable: true })
  dependencies?: string[];

  @Column({ type: 'simple-json', nullable: true })
  metadata?: Record<string, unknown> | null;

  @Column({ type: 'timestamptz', nullable: true })
  installedAt?: Date | null;

  @Column({ type: 'timestamptz', nullable: true })
  upgradedAt?: Date | null;

  @CreateDateColumn({ type: 'timestamptz' })
  createdAt: Date;

  @UpdateDateColumn({ type: 'timestamptz' })
  updatedAt: Date;
}

