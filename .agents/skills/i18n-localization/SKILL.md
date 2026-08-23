---
name: i18n-localization
description: Maintain Workless application and module locale JSON, server-rendered translation usage, locale resolution, and translation completeness.
allowed-tools: Read, Glob, Grep
---

# i18n & Localization

## Project Structure

Workless owns locale loading in `src/workless/i18n.ts`; it does not use `react-i18next` or Next.js locale routing.

```text
src/app/locales/
  en/*.json
  th/*.json
src/modules/<module>/locales/
  en/*.json
  th/*.json
```

- application-wide messages belong in `src/app/locales/<locale>`
- module-owned messages belong in `src/modules/<module>/locales/<locale>`
- `npm run module:create -- <name>` creates the `en` and `th` locale roots
- English is the runtime fallback locale

## Runtime Pattern

Resolve request locale through `resolveLocaleFromRequest(...)`. Current priority is locale cookie, `x-lang`, `accept-language`, then English fallback.

Server-rendered views use the project translator:

```tsx
const { t } = createTranslator(locale, { modules: ['crm'] });
return <h1>{t('crm.dashboard.heading')}</h1>;
```

Pass the owning module name when a view needs module messages. Keep keys namespaced by application area or module.

---

## Best Practices

### DO ✅

- Use translation keys, not raw text
- Namespace translations by feature
- Support pluralization
- Handle date/number formats per locale
- Plan for RTL from the start
- Use ICU message format for complex strings

### DON'T ❌

- Hardcode strings in components
- Concatenate translated strings
- Assume text length (German is 30% longer)
- Forget about RTL layout
- Mix languages in same file

---

## Common Issues

| Issue | Solution |
|-------|----------|
| Missing translation | Fallback to default language |
| Hardcoded strings | Use linter/checker script |
| Date format | Use Intl.DateTimeFormat |
| Number format | Use Intl.NumberFormat |
| Pluralization | Use ICU message format |

---

## RTL Support

```css
/* CSS Logical Properties */
.container {
  margin-inline-start: 1rem;  /* Not margin-left */
  padding-inline-end: 1rem;   /* Not padding-right */
}

[dir="rtl"] .icon {
  transform: scaleX(-1);
}
```

---

## Checklist

Before shipping:

- [ ] All user-facing strings use translation keys
- [ ] Locale files exist for all supported languages
- [ ] Date/number formatting uses Intl API
- [ ] RTL layout tested (if applicable)
- [ ] Fallback language configured
- [ ] No hardcoded strings in components

---

## Script

| Script | Purpose | Command |
|--------|---------|---------|
| `scripts/i18n_checker.py` | Detect hardcoded strings and compare locale keys | `python .agents/skills/i18n-localization/scripts/i18n_checker.py .` |
