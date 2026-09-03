import type { BadgeCommonProps, BadgeTone } from '@/app/views/components/badges/badge.types';

type BadgeStyle = 'solid' | 'soft' | 'outline' | 'plain';

export type BadgeBaseProps = BadgeCommonProps & {
  style?: BadgeStyle;
  rounded?: boolean;
  glow?: boolean;
  dot?: boolean;
};

const solidClasses: Record<BadgeTone, string> = {
  default: 'bg-slate-150 text-slate-800 dark:bg-navy-500 dark:text-navy-100',
  primary: 'bg-accent text-white',
  secondary: 'bg-secondary text-white',
  info: 'bg-info text-white',
  success: 'bg-success text-white',
  warning: 'bg-warning text-white',
  error: 'bg-danger text-white',
  dark: 'bg-navy-700 text-white dark:bg-navy-900',
  light: 'bg-slate-150 text-slate-800',
};

const softClasses: Record<BadgeTone, string> = {
  default: 'bg-slate-150 text-slate-800 dark:bg-navy-500 dark:text-navy-100',
  primary: 'bg-accent/10 text-accent dark:bg-accent-light/15 dark:text-accent-light',
  secondary: 'bg-secondary/10 text-secondary dark:bg-secondary/15',
  info: 'bg-info/10 text-info dark:bg-info/15',
  success: 'bg-success/10 text-success dark:bg-success/15',
  warning: 'bg-warning/10 text-warning dark:bg-warning/15',
  error: 'bg-danger/10 text-danger dark:bg-danger/15',
  dark: 'bg-navy-700/10 text-navy-700 dark:bg-navy-100/15 dark:text-navy-100',
  light: 'bg-slate-100 text-slate-600 dark:bg-navy-500 dark:text-navy-100',
};

const outlineClasses: Record<BadgeTone, string> = {
  default: 'border border-slate-300 text-slate-800 dark:border-navy-450 dark:text-navy-50',
  primary: 'border border-accent text-accent dark:border-accent-light dark:text-accent-light',
  secondary: 'border border-secondary text-secondary',
  info: 'border border-info text-info',
  success: 'border border-success text-success',
  warning: 'border border-warning text-warning',
  error: 'border border-danger text-danger',
  dark: 'border border-navy-700 text-navy-700 dark:border-navy-100 dark:text-navy-100',
  light: 'border border-slate-300 text-slate-600 dark:border-navy-450 dark:text-navy-100',
};

const plainClasses: Record<BadgeTone, string> = {
  default: 'text-slate-800 dark:text-navy-100',
  primary: 'text-accent dark:text-accent-light',
  secondary: 'text-secondary',
  info: 'text-info',
  success: 'text-success',
  warning: 'text-warning',
  error: 'text-danger',
  dark: 'text-navy-700 dark:text-navy-100',
  light: 'text-slate-500 dark:text-navy-200',
};

const glowClasses: Record<BadgeTone, string> = {
  default: 'shadow-[0_3px_10px_0] shadow-slate-200/50 dark:shadow-navy-450/50',
  primary: 'shadow-[0_3px_10px_0] shadow-accent/50',
  secondary: 'shadow-[0_3px_10px_0] shadow-secondary/50',
  info: 'shadow-[0_3px_10px_0] shadow-info/50',
  success: 'shadow-[0_3px_10px_0] shadow-success/50',
  warning: 'shadow-[0_3px_10px_0] shadow-warning/50',
  error: 'shadow-[0_3px_10px_0] shadow-danger/50',
  dark: 'shadow-[0_3px_10px_0] shadow-navy-700/50',
  light: 'shadow-[0_3px_10px_0] shadow-slate-200/50',
};

const styleClasses: Record<BadgeStyle, Record<BadgeTone, string>> = {
  solid: solidClasses,
  soft: softClasses,
  outline: outlineClasses,
  plain: plainClasses,
};

export function BadgeBase({
  children,
  tone = 'default',
  style = 'solid',
  rounded = false,
  glow = false,
  dot = false,
  className = '',
}: BadgeBaseProps) {
  return (
    <span
      className={[
        'inline-flex items-center justify-center px-2 py-1.5 text-center text-xs font-medium leading-none',
        rounded ? 'rounded-full' : 'rounded',
        dot ? 'gap-2.5' : '',
        styleClasses[style][tone],
        glow ? glowClasses[tone] : '',
        className,
      ].filter(Boolean).join(' ')}
    >
      {dot && <span aria-hidden="true" className="size-2 shrink-0 rounded-full bg-current" />}
      <span>{children}</span>
    </span>
  );
}
