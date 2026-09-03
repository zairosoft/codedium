export type SidebarIcon =
  | 'dashboard'
  | 'apps'
  | 'pages'
  | 'forms'
  | 'components'
  | 'elements';

export type SidebarRailItem = {
  label: string;
  href: string;
  icon: SidebarIcon;
  active?: boolean;
};

export type SidebarMenuGroup = {
  label: string;
  items: Array<{
    label: string;
    href: string;
    active?: boolean;
  }>;
};

/**
 * Navigation adapted from Lineone pages-starter-1.html.
 * The demo's .html links are mapped to routes that exist in Workless.
 */
export const sidebarRailItems: SidebarRailItem[] = [
  {
    label: 'Dashboard',
    href: '/api/v1/crm/dashboard/page',
    icon: 'dashboard',
  },
  { label: 'Applications', href: '/', icon: 'apps' },
  { label: 'Pages & Layouts', href: '#layouts', icon: 'pages', active: true },
  { label: 'Forms', href: '/auth/register', icon: 'forms' },
  { label: 'Components', href: '/components', icon: 'components' },
  { label: 'Elements', href: '#elements', icon: 'elements' },
];

export const sidebarMenuGroups: SidebarMenuGroup[] = [
  {
    label: 'Workspace',
    items: [
      { label: 'Overview', href: '/' },
      {
        label: 'CRM Analytics',
        href: '/api/v1/crm/dashboard/page',
        active: true,
      },
    ],
  },
  {
    label: 'Account',
    items: [
      { label: 'Sign in', href: '/auth/login' },
      { label: 'Create account', href: '/auth/register' },
    ],
  },
];
