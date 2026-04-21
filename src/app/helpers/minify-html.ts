import { minify, type Options } from 'html-minifier';

const defaultOptions: Options = {
  collapseWhitespace: true,
  removeComments: true,
  removeRedundantAttributes: true,
  removeEmptyAttributes: true,
  minifyCSS: true,
  minifyJS: true,
  collapseBooleanAttributes: true,
  removeScriptTypeAttributes: true,
  removeStyleLinkTypeAttributes: true,
  useShortDoctype: true,
  sortAttributes: true,
  sortClassName: true,
};

export function minifyHtml(
  input: string,
  options?: Options,
): string {
  return minify(input, { ...defaultOptions, ...options });
}
