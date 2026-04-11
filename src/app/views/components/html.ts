type HtmlShellOptions = {
  title: string;
  body: string;
};

function minifyHtml(html: string): string {
  return html
    .replace(/\n\s*/g, '')       // strip newlines + leading whitespace
    .replace(/>\s+</g, '><')     // collapse whitespace between tags
    .replace(/\s{2,}/g, ' ')     // collapse multiple spaces to one
    .trim();
}

export function html(options: HtmlShellOptions): string {
  const raw = `<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>${options.title}</title>
    <link rel="stylesheet" href="/assets/css/app.css" />
  </head>
  <body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    ${options.body}
  </body>
</html>`;

  return minifyHtml(raw);
}
