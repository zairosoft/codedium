import { readFileSync } from 'node:fs';
import { join } from 'node:path';

const THEME_PATH = join(process.cwd(), 'theme html', 'pages-starter-1.html');
const SIDEBAR_START = '<!-- Sidebar -->';
const MAIN_WRAPPER_START = '<!-- Main Content Wrapper -->';

let cachedSidebarHtml: string | null = null;

function extractBetween(source: string, startMarker: string, endMarker: string): string {
  const startIndex = source.indexOf(startMarker);
  const endIndex = source.indexOf(endMarker);

  if (startIndex === -1 || endIndex === -1 || endIndex <= startIndex) {
    throw new Error(
      `Cannot extract sidebar from theme file. Missing markers: "${startMarker}" / "${endMarker}"`,
    );
  }

  return source.slice(startIndex, endIndex).trim();
}

export function renderSidebarView(): string {
  if (cachedSidebarHtml) {
    return cachedSidebarHtml;
  }

  const html = readFileSync(THEME_PATH, 'utf8');
  cachedSidebarHtml = extractBetween(html, SIDEBAR_START, MAIN_WRAPPER_START);
  return cachedSidebarHtml;
}


