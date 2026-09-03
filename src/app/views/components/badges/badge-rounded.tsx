import { BadgeBase } from '@/app/views/components/badges/badge-base';
import type { BadgeCommonProps } from '@/app/views/components/badges/badge.types';

export type BadgeRoundedProps = BadgeCommonProps;

export function BadgeRounded(props: BadgeRoundedProps) {
  return <BadgeBase {...props} rounded />;
}
