---
name: qa-testing
description: Validate Workless changes in a realistic way. Use when reviewing builds, checking config correctness, verifying active module wiring, or planning tests in this NestJS monolith where automated coverage is still limited.
---

# QA Testing

Use this skill when a task is about validation, testing, review confidence, or release-readiness in Workless.

## Project Reality

Workless does not yet have a mature automated test suite.

Current verification reality:

- `npm run build` is the main baseline check
- `npm test` is still a placeholder unless the repository changes
- runtime verification needs live PostgreSQL
- some cache behavior also needs Redis to be fully exercised

## Primary Goals

Use this skill to:

- decide what can actually be verified in this repo today
- avoid overstating test confidence
- review changes against active files, not legacy duplicates
- suggest the right next test level when automation is missing

## Verification Order

Default order for this repository:

1. Confirm the edited file is active.
2. Run `npm run build`.
3. Inspect affected config and module wiring.
4. State clearly what was not runtime-verified.

## Active Risk Areas

### Legacy Duplicate Files

Some areas, especially CRM, contain old and new files side by side.

Before validating behavior:

1. inspect `src/modules/<name>/module.ts`
2. confirm which controller/service/entity/seeder is actually wired
3. review only that path for behavior claims

### Generated vs Source Assets

Frontend-related checks must distinguish:

- real source files currently wired from the repo
- generated assets: `public/assets/*`

Do not treat generated output as the source of truth when validating theme changes.

Current frontend path:

- `public/assets/css/app.css` is the Tailwind input used by both Vite and the CLI scripts
- `public/assets/css/tailwindcss.css` is generated output
- server-rendered React TSX views live under `src/app/views` and `src/modules/<module>/views`
- verify both the TSX source and CSS build when UI structure or Tailwind classes change

### Environment Validation

When config changes are involved, compare:

- `.env.example`
- `README.md`
- actual env usage in `src/`

Only document variables the code truly uses, unless placeholders are intentionally being kept.

## What To Verify For Common Changes

### Backend / Module Changes

- module imports/providers/controllers still resolve
- DTOs match service inputs
- repository methods remain tenant-aware
- cache invalidation still happens on write paths
- lifecycle wiring still matches module metadata

### Config Changes

- env variable names match `ConfigService` lookups or `process.env` usage
- docs and examples stay aligned
- optional services degrade safely when disabled

### UI / Asset Changes

- source files were edited, not only generated files
- Vite output paths still make sense
- server-rendered usage is still compatible

## How To Report Results

Always separate:

- verified
- not verified
- risky assumptions

Example structure:

- Verified: build passes, active module wiring looks correct
- Not verified: DB migration behavior, Redis runtime behavior
- Risks: legacy duplicate files may confuse future edits

## If Tests Need To Be Added

Recommend the smallest useful next step:

1. unit tests for pure service, policy, or utility logic
2. repository tests only when a test DB setup exists
3. integration/e2e only after the project establishes a real test harness

Do not invent a Jest/Vitest suite if the repo has not actually established one yet.

## Success Criteria

- claims match what was actually verified
- active files were checked instead of stale duplicates
- build confidence is clear and honest
- missing runtime/test coverage is stated explicitly
