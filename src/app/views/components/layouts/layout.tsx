import type { ReactNode } from 'react';
import { render } from '@/app/views/components/main';
import {
  sidebarMenuGroups,
  sidebarRailItems,
  type SidebarIcon,
} from '@/app/views/components/layouts/common/sidebar';

type MainLayoutOptions = {
  title?: string;
  content?: ReactNode;
  includeSidebar?: boolean;
};

function SidebarIconView({ icon }: { icon: SidebarIcon }) {
  if (icon === 'dashboard') {
    return (
      <svg className="size-7" viewBox="0 0 24 24" fill="none">
        <path fill="currentColor" fillOpacity=".3" d="M5 14.06c0-1.01 0-1.52.22-1.95.22-.43.63-.72 1.46-1.31l4.16-2.97c.56-.4.84-.6 1.16-.6s.6.2 1.16.6l4.17 2.97c.82.59 1.23.88 1.45 1.31.22.43.22.94.22 1.95V19c0 .94 0 1.41-.29 1.71S17.94 21 17 21H7c-.94 0-1.41 0-1.71-.29S5 19.94 5 19v-4.94Z" />
        <path fill="currentColor" d="M3 12.39c0 .27 0 .4.08.44.09.04.19-.04.4-.2l7.29-5.68c.59-.46.89-.68 1.23-.68s.64.22 1.23.68l7.29 5.68c.21.16.31.24.4.2.08-.04.08-.17.08-.44v-.41c0-.48 0-.72-.1-.93-.1-.21-.29-.36-.67-.65l-7-5.45c-.59-.46-.89-.68-1.23-.68s-.64.22-1.23.68l-7 5.45c-.38.29-.57.44-.67.65-.1.21-.1.45-.1.93v.41Z" />
      </svg>
    );
  }

  if (icon === 'apps') {
    return (
      <svg className="size-7" viewBox="0 0 24 24" fill="none">
        <path fill="currentColor" fillOpacity=".3" d="M5 8h14v8c0 1.89 0 2.83-.59 3.41C17.83 20 16.89 20 15 20H9c-1.89 0-2.83 0-3.41-.59C5 18.83 5 17.89 5 16V8Z" />
        <rect x="4" y="8" width="16" height="3" rx="1" fill="currentColor" />
        <path d="M12 8 11.76 5.85A2.66 2.66 0 0 0 9.12 3.5 2.62 2.62 0 0 0 7.66 8.27L9.5 9.5M12 8l.24-2.15a2.66 2.66 0 0 1 2.64-2.35 2.62 2.62 0 0 1 1.46 4.77L14.5 9.5M12 11v4" stroke="currentColor" strokeLinecap="round" />
      </svg>
    );
  }

  if (icon === 'pages') {
    return (
      <svg className="size-7" viewBox="0 0 24 24" fill="none">
        <path fill="currentColor" d="M9.86 3H4.14A1.14 1.14 0 0 0 3 4.14v5.72A1.14 1.14 0 0 0 4.14 11h5.72A1.14 1.14 0 0 0 11 9.86V4.14A1.14 1.14 0 0 0 9.86 3Z" />
        <path fill="currentColor" fillOpacity=".3" d="M9.86 12.9H4.14A1.14 1.14 0 0 0 3 14.04v5.72a1.14 1.14 0 0 0 1.14 1.14h5.72A1.14 1.14 0 0 0 11 19.76v-5.72a1.14 1.14 0 0 0-1.14-1.14ZM19.76 3h-5.72a1.14 1.14 0 0 0-1.14 1.14v5.72A1.14 1.14 0 0 0 14.04 11h5.72a1.14 1.14 0 0 0 1.14-1.14V4.14A1.14 1.14 0 0 0 19.76 3Zm0 9.9h-5.72a1.14 1.14 0 0 0-1.14 1.14v5.72a1.14 1.14 0 0 0 1.14 1.14h5.72a1.14 1.14 0 0 0 1.14-1.14v-5.72a1.14 1.14 0 0 0-1.14-1.14Z" />
      </svg>
    );
  }

  if (icon === 'forms') {
    return (
      <svg className="size-7" viewBox="0 0 24 24" fill="none">
        <path fill="currentColor" d="M4 4h16v4H4zM4 10h7v10H4z" />
        <path fill="currentColor" fillOpacity=".3" d="M13 10h7v4h-7zM13 16h7v4h-7z" />
      </svg>
    );
  }

  if (icon === 'components') {
    return (
      <svg className="size-7" viewBox="0 0 24 24" fill="none">
        <circle cx="12" cy="9" r="5.25" fill="currentColor" />
        <circle cx="9" cy="16" r="5.25" fill="currentColor" fillOpacity=".5" />
        <circle cx="16" cy="16" r="5.25" fill="currentColor" fillOpacity=".3" />
      </svg>
    );
  }

  return (
    <svg className="size-7" viewBox="0 0 24 24" fill="none">
      <path fill="currentColor" d="M12 3 3 8v8l9 5 9-5V8l-9-5Zm0 2.3L17.7 8 12 10.7 6.3 8 12 5.3Z" />
      <path fill="currentColor" fillOpacity=".3" d="m5 9.6 6 2.9v5.7l-6-3.3V9.6Zm14 0v5.3l-6 3.3v-5.7l6-2.9Z" />
    </svg>
  );
}

function Sidebar() {
  return (
    <>
      <label
        htmlFor="lineone-sidebar-toggle"
        aria-label="Close sidebar"
        className="pointer-events-none fixed inset-0 z-20 bg-slate-900/50 opacity-0 transition-opacity peer-checked/sidebar:pointer-events-auto peer-checked/sidebar:opacity-100 md:hidden"
      />
      <aside className="fixed inset-y-0 left-0 z-40 w-[4.5rem] -translate-x-full transition-transform duration-200 ease-in-out peer-checked/sidebar:translate-x-0 md:translate-x-0 xl:w-[5rem]">
        <div className="flex h-full w-full flex-col items-center border-r border-slate-150 bg-white dark:border-navy-700 dark:bg-navy-800">
          <a href="/" className="flex pt-4" aria-label="Workless home">
            <img className="size-11 object-contain transition-transform duration-500 ease-in-out hover:rotate-[360deg]" src="/assets/images/app-logo.svg" alt="Workless" />
          </a>

          <nav className="is-scrollbar-hidden flex grow flex-col gap-4 overflow-y-auto pt-6" aria-label="Main navigation">
            {sidebarRailItems.map((item) => (
              <a
                key={item.label}
                href={item.href}
                title={item.label}
                aria-label={item.label}
                className={`flex size-11 items-center justify-center rounded-lg outline-hidden transition-colors duration-200 ${item.active
                  ? 'bg-accent/10 text-accent hover:bg-accent/20 dark:bg-navy-600 dark:text-accent-light'
                  : 'text-slate-500 hover:bg-accent/20 hover:text-accent dark:text-navy-200 dark:hover:bg-navy-300/20 dark:hover:text-accent-light'}`}
              >
                <SidebarIconView icon={item.icon} />
              </a>
            ))}
          </nav>

          <div className="flex flex-col items-center gap-3 py-3">
            <a href="/auth/login" className="flex size-11 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-accent/20 hover:text-accent" title="Settings" aria-label="Settings">
              <svg className="size-7" viewBox="0 0 24 24" fill="none">
                <path fill="currentColor" fillOpacity=".3" d="M2 12.95v-1.77c0-1.05.85-1.92 1.9-1.92 1.81 0 2.55-1.29 1.64-2.87a1.92 1.92 0 0 1 .7-2.6l1.73-1c.79-.47 1.81-.19 2.28.61l.11.19c.9 1.58 2.38 1.58 3.29 0l.11-.19c.47-.8 1.49-1.08 2.28-.61l1.73 1a1.92 1.92 0 0 1 .7 2.6c-.91 1.58-.17 2.87 1.64 2.87 1.04 0 1.9.86 1.9 1.92v1.77c0 1.05-.85 1.91-1.9 1.91-1.81 0-2.55 1.29-1.64 2.87.52.92.21 2.08-.7 2.61l-1.73 1c-.79.47-1.81.19-2.28-.61l-.11-.19c-.9-1.58-2.38-1.58-3.29 0l-.11.19c-.47.8-1.49 1.08-2.28.61l-1.73-1a1.92 1.92 0 0 1-.7-2.61c.91-1.58.17-2.87-1.64-2.87A1.91 1.91 0 0 1 2 12.95Z" />
                <circle cx="12" cy="12.06" r="3.27" fill="currentColor" />
              </svg>
            </a>
            <a href="/auth/login" className="relative flex size-12 items-center justify-center rounded-full bg-slate-200 text-sm font-semibold text-slate-700 ring-2 ring-white dark:bg-navy-700 dark:text-navy-100 dark:ring-navy-800" aria-label="Open profile">
              ZS
              <span className="absolute right-0 bottom-0 size-3.5 rounded-full border-2 border-white bg-success dark:border-navy-800" />
            </a>
          </div>
        </div>
      </aside>

      <aside className="fixed inset-y-0 left-0 z-30 w-[calc(4.5rem+15rem)] -translate-x-full shadow-lg transition-transform duration-300 ease-in-out peer-checked/sidebar:translate-x-0 md:peer-checked/sidebar:translate-x-0 xl:w-[20rem] xl:translate-x-0 xl:peer-checked/sidebar:-translate-x-full">
        <div id="layouts" className="flex h-full w-full flex-col bg-white pl-[4.5rem] dark:bg-navy-800 xl:pl-[5rem]">
          <div className="flex h-[4.5rem] shrink-0 items-center justify-between pl-5 pr-2">
            <p className="text-base tracking-wider text-slate-800 dark:text-navy-100">Layouts</p>
            <label htmlFor="lineone-sidebar-toggle" className="flex size-8 cursor-pointer items-center justify-center rounded-full text-accent hover:bg-slate-300/20" aria-label="Close navigation panel">
              <svg className="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="m15 19-7-7 7-7" />
              </svg>
            </label>
          </div>

          <nav className="is-scrollbar-hidden grow overflow-y-auto px-4 pb-6 font-inter" aria-label="Layout navigation">
            {sidebarMenuGroups.map((group, index) => (
              <section key={group.label} className={index ? 'mt-4 border-t border-slate-200 pt-4 dark:border-navy-500' : ''}>
                <h2 className="mb-1 px-2 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-navy-300">{group.label}</h2>
                <ul className="flex flex-col">
                  {group.items.map((item) => (
                    <li key={item.label}>
                      <a href={item.href} className={`flex items-center gap-2 rounded-md px-2 py-2 text-xs-plus tracking-wide outline-hidden transition-all duration-300 hover:pl-3 ${item.active
                        ? 'font-medium text-accent dark:text-accent-light'
                        : 'text-slate-600 hover:text-slate-900 dark:text-navy-200 dark:hover:text-navy-50'}`}>
                        <span className="size-1.5 rounded-full border border-current opacity-50" />
                        {item.label}
                      </a>
                    </li>
                  ))}
                </ul>
              </section>
            ))}
          </nav>
        </div>
      </aside>
    </>
  );
}

function Header() {
  return (
    <header className="fixed inset-x-0 top-0 z-20 h-[61px] border-b border-slate-150 bg-white/95 backdrop-blur-sm transition-[left] duration-300 ease-in-out dark:border-navy-700 dark:bg-navy-800/95 md:left-[4.5rem] md:peer-checked/sidebar:left-[19.5rem] xl:left-[20rem] xl:peer-checked/sidebar:left-[5rem]">
      <div className="flex h-full items-center justify-between px-[var(--margin-x)]">
        <div className="flex items-center">
          <label htmlFor="lineone-sidebar-toggle" className="flex size-8 cursor-pointer flex-col justify-center gap-1.5 text-accent" aria-label="Toggle sidebar">
            <span className="block h-0.5 w-5 bg-current" />
            <span className="block h-0.5 w-3 bg-current" />
            <span className="block h-0.5 w-5 bg-current" />
          </label>
        </div>

        <div className="flex items-center gap-1.5 sm:gap-2">
          <label className="relative mr-2 hidden h-8 sm:flex">
            <span className="pointer-events-none absolute inset-y-0 left-0 flex w-9 items-center justify-center text-slate-400">
              <svg className="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="m21 21-4.35-4.35m2.35-5.15a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z" /></svg>
            </span>
            <input aria-label="Search" placeholder="Search here..." className="form-input h-full w-60 rounded-full border-0 bg-slate-150 py-0 pr-4 pl-9 text-xs-plus text-slate-800 transition-all hover:bg-slate-200 focus:w-80 dark:bg-navy-900 dark:text-navy-100" />
          </label>
          <button type="button" className="flex size-8 items-center justify-center rounded-full text-amber-500 transition-colors hover:bg-slate-150" aria-label="Toggle theme">
            <svg className="size-5.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.75a.75.75 0 0 1 .75.75V5a.75.75 0 0 1-1.5 0V3.5a.75.75 0 0 1 .75-.75ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm8.5 4.25a.75.75 0 0 1 0 1.5H19a.75.75 0 0 1 0-1.5h1.5ZM5 11.25a.75.75 0 0 1 0 1.5H3.5a.75.75 0 0 1 0-1.5H5Zm12.3-5.61a.75.75 0 0 1 1.06 1.06L17.3 7.76a.75.75 0 1 1-1.06-1.06l1.06-1.06ZM6.7 16.24a.75.75 0 0 1 1.06 1.06L6.7 18.36a.75.75 0 0 1-1.06-1.06l1.06-1.06Zm10.6 0 1.06 1.06a.75.75 0 0 1-1.06 1.06l-1.06-1.06a.75.75 0 0 1 1.06-1.06ZM6.7 5.64 7.76 6.7A.75.75 0 0 1 6.7 7.76L5.64 6.7A.75.75 0 0 1 6.7 5.64ZM12 19a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 12 19Z" /></svg>
          </button>
          <button type="button" className="flex size-8 items-center justify-center rounded-full text-info transition-colors hover:bg-slate-150" aria-label="Theme settings">
            <svg className="size-5.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3a9 9 0 0 0 0 18h1.35a1.65 1.65 0 0 0 .76-3.12 1.65 1.65 0 0 1 .76-3.12H17A4 4 0 0 0 21 10.7C20.82 6.35 16.85 3 12 3ZM7.5 12A1.5 1.5 0 1 1 7.5 9a1.5 1.5 0 0 1 0 3Zm2-4A1.5 1.5 0 1 1 9.5 5a1.5 1.5 0 0 1 0 3Zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm2 4a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Z" /></svg>
          </button>
          <button type="button" className="relative flex size-9 items-center justify-center rounded-full text-slate-500 transition-colors hover:bg-slate-150 hover:text-accent" aria-label="Notifications">
            <svg className="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M14.86 17H9.14m9.72 0H5.14c1.47-1.56 2.24-3.64 2.14-5.78V9a4.72 4.72 0 1 1 9.44 0v2.22c-.1 2.14.67 4.22 2.14 5.78ZM14 20h-4" /></svg>
            <span className="absolute right-1.5 top-1.5 size-2 rounded-full bg-accent ring-2 ring-white" />
          </button>
          <button type="button" className="flex size-8 items-center justify-center rounded-full text-slate-500 transition-colors hover:bg-slate-150 hover:text-accent" aria-label="Applications">
            <svg className="size-5" viewBox="0 0 24 24" fill="currentColor"><path d="M4 3h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Zm12 0h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1ZM4 15h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1Zm12 0h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1Z" /></svg>
          </button>
        </div>
      </div>
    </header>
  );
}

export function renderMainLayoutView(options: MainLayoutOptions = {}): string {
  const title = options.title ?? 'Workless';
  const includeSidebar = options.includeSidebar !== false;

  return render({
    title,
    bodyClassName: 'min-h-100vh bg-slate-50 text-slate-500 antialiased dark:bg-navy-900 dark:text-navy-200',
    head: (
      <>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
      </>
    ),
    children: (
      <div className="min-h-100vh flex grow bg-slate-50 dark:bg-navy-900">
        {includeSidebar && (
          <>
            <input id="lineone-sidebar-toggle" type="checkbox" className="peer/sidebar sr-only" />
            <Sidebar />
            <Header />
          </>
        )}
        <main className={`grid w-full min-w-0 place-content-start pb-8 transition-[margin] duration-300 ease-in-out ${includeSidebar ? 'mt-[61px] md:ml-[4.5rem] md:peer-checked/sidebar:ml-[19.5rem] xl:ml-[20rem] xl:peer-checked/sidebar:ml-[5rem]' : ''}`}>
          <div className="w-full px-[var(--margin-x)]">
            <div className="flex items-center gap-4 py-5 lg:py-6">
              <h1 className="text-xl font-medium text-slate-800 dark:text-navy-50 lg:text-2xl">{title}</h1>
            </div>
            {options.content}
          </div>
        </main>
      </div>
    ),
  });
}
