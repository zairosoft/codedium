import type { ReactNode } from 'react';

export type BadgeTone =
  | 'primary'
  | 'success'
  | 'warning'
  | 'danger'
  | 'info'
  | 'neutral';

export type BadgeProps = {
  children: ReactNode;
  tone?: BadgeTone;
  className?: string;
};

const toneClasses: Record<BadgeTone, string> = {
  primary: 'bg-primary/10 text-primary dark:bg-accent/20 dark:text-accent-light',
  success: 'bg-success/10 text-success dark:bg-success/20',
  warning: 'bg-warning/10 text-warning dark:bg-warning/20',
  danger: 'bg-danger/10 text-danger dark:bg-danger/20',
  info: 'bg-info/10 text-info dark:bg-info/20',
  neutral: 'bg-slate-100 text-slate-600 dark:bg-navy-600 dark:text-navy-100',
};

/** A small status label for server-rendered views. */
export function Badge({ children, tone = 'neutral', className = '' }: BadgeProps) {
  return (
    <span
      className={[
        'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold leading-none',
        toneClasses[tone],
        className,
      ].filter(Boolean).join(' ')}
    >
      {children}
    </span>
  );
}
