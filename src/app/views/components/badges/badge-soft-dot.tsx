import { BadgeBase } from '@/app/views/components/badges/badge-base';
import type { BadgeCommonProps } from '@/app/views/components/badges/badge.types';

export type BadgeSoftDotProps = BadgeCommonProps;

export function BadgeSoftDot(props: BadgeSoftDotProps) {
  return <BadgeBase {...props} style="soft" rounded dot />;
}
