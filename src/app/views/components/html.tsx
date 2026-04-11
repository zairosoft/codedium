import * as Html from '@kitajs/html';

type HtmlOptions = {
  title: string;
  children: Html.Children;
};

function minifyHtml(input: string): string {
  return input
    .replace(/\n\s*/g, '')
    .replace(/>\s+</g, '><')
    .replace(/\s{2,}/g, ' ')
    .trim();
}

export function html(options: HtmlOptions): string {
  const raw = (
    <html lang="en">
      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{options.title}</title>
        <link rel="stylesheet" href="/assets/css/app.css" />
      </head>
      <body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
        {options.children}
      </body>
    </html>
  ) as string;

  return '<!DOCTYPE html>' + minifyHtml(raw);
}
