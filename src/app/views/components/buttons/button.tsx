import type { ButtonHTMLAttributes, ReactNode } from 'react';

export type ButtonVariant = 'primary' | 'secondary' | 'danger' | 'outline';

export type ButtonProps = ButtonHTMLAttributes<HTMLButtonElement> & {
  children: ReactNode;
  variant?: ButtonVariant;
};

const variantClasses: Record<ButtonVariant, string> = {
  primary:
    'bg-primary text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus',
  secondary:
    'bg-slate-200 text-slate-800 hover:bg-slate-300 focus:bg-slate-300 dark:bg-navy-600 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500',
  danger:
    'bg-danger text-white hover:bg-danger/90 focus:bg-danger/90 active:bg-danger/80',
  outline:
    'border border-slate-300 bg-white text-slate-700 hover:bg-slate-100 focus:bg-slate-100 dark:border-navy-500 dark:bg-navy-700 dark:text-navy-100 dark:hover:bg-navy-600 dark:focus:bg-navy-600',
};

/** A reusable themed button for server-rendered views. */
export function Button({
  children,
  className = '',
  variant = 'primary',
  type = 'button',
  ...props
}: ButtonProps) {
  return (
    <button
      type={type}
      className={[
        'btn inline-flex items-center justify-center gap-2 font-medium disabled:cursor-not-allowed disabled:opacity-75',
        variantClasses[variant],
        className,
      ].filter(Boolean).join(' ')}
      {...props}
    >
      {children}
    </button>
  );
}
