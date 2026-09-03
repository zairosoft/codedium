import { BadgeBase } from '@/app/views/components/badges/badge-base';
import type { BadgeCommonProps } from '@/app/views/components/badges/badge.types';

export type BadgeOutlinedProps = BadgeCommonProps;

export function BadgeOutlined(props: BadgeOutlinedProps) {
  return <BadgeBase {...props} style="outline" rounded />;
}
