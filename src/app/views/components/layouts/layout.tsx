import { RawHtml, render } from '../main';
import { renderSidebarView } from './common/sidebar';

type MainLayoutOptions = {
  title?: string;
  content?: string;
  includeSidebar?: boolean;
};

export function renderMainLayoutView(options: MainLayoutOptions = {}): string {
  const title = options.title ?? 'Zairosoft Platform';
  const sidebar = options.includeSidebar === false ? '' : renderSidebarView();

  return render({
    title,
    children: (
      <div className="app-container flex min-h-screen">
        <RawHtml html={sidebar} asContents />
        <main className="main-content w-full px-[var(--margin-x)] pb-8">
          <div className="flex items-center space-x-4 py-5 lg:py-6">
            <h2 className="text-xl font-medium text-slate-800 dark:text-navy-50 lg:text-2xl">
              {title}
            </h2>
          </div>
          <RawHtml html={options.content ?? ''} asContents />
        </main>
      </div>
    ),
  });
}
