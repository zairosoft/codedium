import { BadgeBase } from '@/app/views/components/badges/badge-base';
import type { BadgeCommonProps } from '@/app/views/components/badges/badge.types';

export type BadgeDotProps = BadgeCommonProps;

export function BadgeDot(props: BadgeDotProps) {
  return <BadgeBase {...props} style="plain" dot />;
}
