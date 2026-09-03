import { BadgeBase } from '@/app/views/components/badges/badge-base';
import type { BadgeCommonProps } from '@/app/views/components/badges/badge.types';

export type BadgeGlowProps = BadgeCommonProps;

export function BadgeGlow(props: BadgeGlowProps) {
  return <BadgeBase {...props} glow />;
}
