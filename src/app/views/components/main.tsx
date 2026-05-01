import type { PropsWithChildren, ReactNode } from "react";
import { renderToStaticMarkup } from "react-dom/server";
import { minify } from "../../../workless/minify";
import { createTranslator, type AppLocale } from "../../../workless/i18n";

export { createTranslator, type AppLocale };

export type HtmlDocumentProps = PropsWithChildren<{
  title: string;
  bodyClassName?: string;
  head?: ReactNode;
  htmlClassName?: string;
  locale?: AppLocale;
  bodyProps?: Record<string, any>;
}>;

type RawHtmlProps = {
  html: string;
  asContents?: boolean;
};

export function Raw({ html, asContents = false }: RawHtmlProps) {
  return (
    <div
      dangerouslySetInnerHTML={{ __html: html }}
      style={asContents ? { display: "contents" } : undefined}
    />
  );
}

export { Raw as RawHtml };

export function Html({
  title,
  bodyClassName,
  head,
  htmlClassName,
  locale = "en",
  bodyProps = {},
  children,
}: HtmlDocumentProps) {
  return (
    <html lang={locale} className={htmlClassName}>
      <head>
        <meta charSet="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="turbo-refresh-method" content="morph" />
        <title>{title}</title>
        <link rel="stylesheet" href="/assets/css/tailwindcss.css" />
        <script
          type="module"
          src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.13/+esm"
        ></script>
        {head}
      </head>
      <body
        className={
          bodyClassName ?? "min-h-screen bg-slate-50 text-slate-800 antialiased"
        }
        {...bodyProps}
      >
        {children}
      </body>
    </html>
  );
}

export function Render(props: HtmlDocumentProps): string {
  const raw = renderToStaticMarkup(<Html {...props} />);
  return "<!DOCTYPE html>" + minify(raw);
}

export type ViewContext = {
  t: (key: string) => string;
  locale: AppLocale;
  isLang: boolean;
};

export function createView<TOptions>(
  builder: (ctx: ViewContext, options: TOptions) => HtmlDocumentProps,
) {
  return function renderView(options: TOptions & { locale?: AppLocale } = {} as any): string {
    const locale = options?.locale ?? (process.env.LOCALE as AppLocale) ?? "en";
    const { t } = createTranslator(locale);
    const isLang = locale === "th";

    return Render({
      locale,
      ...builder({ t, locale, isLang }, options),
    });
  };
}

export { Render as render };
