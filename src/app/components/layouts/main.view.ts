import { readFileSync } from 'node:fs';
import { join } from 'node:path';
import { renderSidebarView } from './common/sidebar.view';

const THEME_PATH = join(process.cwd(), 'theme html', 'pages-starter-1.html');
const SIDEBAR_START = '<!-- Sidebar -->';
const MAIN_WRAPPER_START = '<!-- Main Content Wrapper -->';
const MAIN_END = '</main>';

type MainLayoutOptions = {
  title?: string;
  content?: string;
  includeSidebar?: boolean;
};

type ThemeFragments = {
  beforeSidebar: string;
  afterMain: string;
};

let cachedFragments: ThemeFragments | null = null;

function readThemeFragments(): ThemeFragments {
  if (cachedFragments) {
    return cachedFragments;
  }

  const themeHtml = readFileSync(THEME_PATH, 'utf8');

  const sidebarIndex = themeHtml.indexOf(SIDEBAR_START);
  const mainWrapperIndex = themeHtml.indexOf(MAIN_WRAPPER_START);
  const mainEndIndex = themeHtml.indexOf(MAIN_END, mainWrapperIndex);

  if (sidebarIndex === -1 || mainWrapperIndex === -1 || mainEndIndex === -1) {
    throw new Error('Cannot build main layout from theme file. Required markers were not found.');
  }

  cachedFragments = {
    beforeSidebar: themeHtml.slice(0, sidebarIndex),
    afterMain: themeHtml.slice(mainEndIndex + MAIN_END.length),
  };

  return cachedFragments;
}

function renderMainContent(title: string, content: string): string {
  return `      <!-- Main Content Wrapper -->
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
  const { beforeSidebar, afterMain } = readThemeFragments();
  const title = options.title ?? 'Zairosoft Platform';
  const content = options.content ?? '';
  const sidebar = options.includeSidebar === false ? '' : `      ${renderSidebarView()}\n\n`;
  const mainContent = renderMainContent(title, content);

  return `${beforeSidebar}${sidebar}${mainContent}${afterMain}`;
}


