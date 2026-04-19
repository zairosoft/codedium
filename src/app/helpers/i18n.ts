import type { Request } from 'express';
import appEnMessages from '../locales/en/common.json';
import appThMessages from '../locales/th/common.json';
import { crmEnMessages } from '../../modules/crm/locales/en/common';
import { crmThMessages } from '../../modules/crm/locales/th/common';

export const supportedLocales = ['en', 'th'] as const;

export type AppLocale = (typeof supportedLocales)[number];
export type ModuleLocaleName = 'crm';

interface MessageTree {
  [key: string]: string | MessageTree;
}

const appLocales: Record<AppLocale, MessageTree> = {
  en: appEnMessages as MessageTree,
  th: appThMessages as MessageTree,
};

const moduleLocales: Record<ModuleLocaleName, Record<AppLocale, MessageTree>> = {
  crm: {
    en: crmEnMessages as MessageTree,
    th: crmThMessages as MessageTree,
  },
};

function isObject(value: unknown): value is MessageTree {
  return typeof value === 'object' && value !== null && !Array.isArray(value);
}

function deepMerge(target: MessageTree, source: MessageTree): MessageTree {
  const output: MessageTree = { ...target };

  for (const [key, value] of Object.entries(source)) {
    const current = output[key];

    if (isObject(current) && isObject(value)) {
      output[key] = deepMerge(current, value);
      continue;
    }

    output[key] = value;
  }

  return output;
}

function getByPath(messages: MessageTree, key: string): string | undefined {
  const parts = key.split('.');
  let current: string | MessageTree | undefined = messages;

  for (const part of parts) {
    if (!isObject(current)) {
      return undefined;
    }

    current = current[part];
  }

  return typeof current === 'string' ? current : undefined;
}

export function resolveLocale(input?: string | null): AppLocale {
  if (!input) {
    return 'en';
  }

  const normalized = input.toLowerCase().trim();
  const exact = supportedLocales.find((locale) => locale === normalized);
  if (exact) {
    return exact;
  }

  const language = normalized.split(/[-_,;]/)[0];
  const partial = supportedLocales.find((locale) => locale === language);
  return partial ?? 'en';
}

export function resolveLocaleFromRequest(request: Pick<Request, 'query' | 'headers'>): AppLocale {
  const queryLang =
    typeof request.query.lang === 'string'
      ? request.query.lang
      : Array.isArray(request.query.lang) && typeof request.query.lang[0] === 'string'
        ? request.query.lang[0]
        : undefined;

  if (queryLang) {
    return resolveLocale(queryLang);
  }

  const headerLang =
    typeof request.headers['x-lang'] === 'string'
      ? request.headers['x-lang']
      : typeof request.headers['accept-language'] === 'string'
        ? request.headers['accept-language']
        : undefined;

  return resolveLocale(headerLang);
}

export function withLocale(path: string, locale: AppLocale): string {
  const separator = path.includes('?') ? '&' : '?';
  return `${path}${separator}lang=${locale}`;
}

export function createTranslator(
  locale: AppLocale,
  options: { modules?: ModuleLocaleName[] } = {},
) {
  let messages = appLocales.en;

  if (locale !== 'en') {
    messages = deepMerge(messages, appLocales[locale]);
  }

  for (const moduleName of options.modules ?? []) {
    messages = deepMerge(messages, moduleLocales[moduleName].en);

    if (locale !== 'en') {
      messages = deepMerge(messages, moduleLocales[moduleName][locale]);
    }
  }

  return {
    locale,
    t(key: string): string {
      return getByPath(messages, key) ?? key;
    },
  };
}
