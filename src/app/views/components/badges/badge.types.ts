import type { ReactNode } from 'react';

export type BadgeTone =
  | 'default'
  | 'primary'
  | 'secondary'
  | 'info'
  | 'success'
  | 'warning'
  | 'error'
  | 'dark'
  | 'light';

export type BadgeCommonProps = {
  children: ReactNode;
  tone?: BadgeTone;
  className?: string;
};
