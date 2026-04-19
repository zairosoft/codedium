import type { ReactNode } from 'react';
import { renderToStaticMarkup } from 'react-dom/server';
import { minifyHtml } from '../../helpers/minify-html';

type HtmlOptions = {
  title: string;
  children?: ReactNode;
  bodyHtml?: string;
  bodyClassName?: string;
  head?: ReactNode;
  htmlClassName?: string;
};

export function html(options: HtmlOptions): string {
  const raw = renderToStaticMarkup(
    <html lang="en" className={options.htmlClassName}>
      <head>
        <meta charSet="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{options.title}</title>
        <link rel="stylesheet" href="/assets/css/tailwindcss.css" />
        {options.head}
      </head>
      {options.bodyHtml
        ? (
            <body
              className={options.bodyClassName ?? 'min-h-screen bg-slate-50 text-slate-800 antialiased'}
              dangerouslySetInnerHTML={{ __html: options.bodyHtml }}
            />
          )
        : (
            <body className={options.bodyClassName ?? 'min-h-screen bg-slate-50 text-slate-800 antialiased'}>
              {options.children}
            </body>
          )}
    </html>,
  );

  return '<!DOCTYPE html>' + minifyHtml(raw);
}
