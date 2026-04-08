import { SetMetadata } from '@nestjs/common';

export const HTML_CACHE_METADATA = Symbol('HTML_CACHE_METADATA');

export type HtmlCacheOptions = {
  maxAgeSeconds: number;
  scope?: 'public' | 'private';
  vary?: string[];
  surrogateKey?: string;
};

export function HtmlCacheable(options: HtmlCacheOptions): MethodDecorator & ClassDecorator {
  return SetMetadata(HTML_CACHE_METADATA, options);
}

