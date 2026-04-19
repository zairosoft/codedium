export function minifyHtml(input: string): string {
  return input
    .replace(/\n\s*/g, '')
    .replace(/>\s+</g, '><')
    .replace(/\s{2,}/g, ' ')
    .trim();
}
