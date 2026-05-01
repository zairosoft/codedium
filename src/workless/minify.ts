import { minify as htmlMinify, type Options } from 'html-minifier';

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

export function minify(
  input: string,
  options?: Options,
): string {
  return htmlMinify(input, { ...defaultOptions, ...options });
}
