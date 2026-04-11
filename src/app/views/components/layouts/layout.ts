import { html } from '../../components/html';
import { renderSidebarView } from './common/sidebar';

type MainLayoutOptions = {
  title?: string;
  content?: string;
  includeSidebar?: boolean;
};

function renderMainContent(title: string, content: string): string {
  return `
      <main class="main-content w-full px-[var(--margin-x)] pb-8">
        <div class="flex items-center space-x-4 py-5 lg:py-6">
          <h2 class="text-xl font-medium text-slate-800 dark:text-navy-50 lg:text-2xl">
            ${title}
          </h2>
        </div>
        ${content}
      </main>`;
}

export function renderMainLayoutView(options: MainLayoutOptions = {}): string {
  const title = options.title ?? 'Zairosoft Platform';
  const content = options.content ?? '';
  const sidebar = options.includeSidebar === false ? '' : renderSidebarView();
  const mainContent = renderMainContent(title, content);

  return html({
    title,
    body: `
    <div class="app-container flex min-h-screen">
      ${sidebar}
      ${mainContent}
    </div>`,
  });
}
