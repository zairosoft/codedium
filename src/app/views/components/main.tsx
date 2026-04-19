import type { PropsWithChildren, ReactNode } from 'react';
import { renderToStaticMarkup } from 'react-dom/server';
import { minifyHtml } from '../../helpers/minify-html';

export type HtmlDocumentProps = PropsWithChildren<{
  title: string;
  bodyClassName?: string;
  head?: ReactNode;
  htmlClassName?: string;
}>;

type RawHtmlProps = {
  html: string;
  asContents?: boolean;
};

export function Raw({ html, asContents = false }: RawHtmlProps) {
  return (
    <div
      dangerouslySetInnerHTML={{ __html: html }}
      style={asContents ? { display: 'contents' } : undefined}
    />
  );
}

export function Html({
  title,
  bodyClassName,
  head,
  htmlClassName,
  children,
}: HtmlDocumentProps) {
  return (
    <html lang="en" className={htmlClassName}>
      <head>
        <meta charSet="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{title}</title>
        <link rel="stylesheet" href="/assets/css/tailwindcss.css" />
        {head}
      </head>
      <body className={bodyClassName ?? 'min-h-screen bg-slate-50 text-slate-800 antialiased'}>
        {children}
      </body>
    </html>
  );
}

export function Render(props: HtmlDocumentProps): string {
  const raw = renderToStaticMarkup(<Html {...props} />);
  return '<!DOCTYPE html>' + minifyHtml(raw);
}
