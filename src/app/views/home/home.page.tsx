import * as Html from '@kitajs/html';
import { html } from '../components/html';

export function renderHomePage(): string {
  return html({
    title: 'Workless Home',
    children: (
      <main class="mx-auto max-w-5xl px-4 py-14">
        <section class="rounded-3xl border border-slate-200/60 bg-white/80 p-8 shadow-xl backdrop-blur-sm">
          <span class="inline-flex rounded-full bg-teal-700/10 px-3 py-1.5 text-xs font-bold uppercase tracking-widest text-teal-800">
            Workless Platform
          </span>
          <h1 class="mt-5 text-5xl font-extrabold leading-tight tracking-tight text-slate-900" style="max-width:10ch">
            Home page is live.
          </h1>
          <p class="mt-3 max-w-xl text-base leading-relaxed text-slate-500">
            The root route now serves a real HTML page. Your API stays under
            <code class="rounded-lg bg-slate-100 px-2 py-0.5 text-sm font-medium text-slate-700">/api/v1</code>,
            and the app is connected to the local PostgreSQL instance from ServBay.
          </p>
          <div class="mt-6 flex flex-wrap gap-3">
            <a
              class="inline-flex items-center justify-center rounded-xl bg-gradient-to-br from-teal-600 to-teal-800 px-5 py-3 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5"
              href="/api/v1/auth/login"
            >
              Sign In
            </a>
            <a
              class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/70 px-5 py-3 text-sm font-bold text-slate-700 transition hover:-translate-y-0.5"
              href="/api/v1/modules"
            >
              Open system modules
            </a>
            <a
              class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/70 px-5 py-3 text-sm font-bold text-slate-700 transition hover:-translate-y-0.5"
              href="/api/v1/crm/dashboard/page"
            >
              Open CRM dashboard
            </a>
          </div>
        </section>

        <section class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-3">
          <article class="rounded-2xl border border-slate-200/60 bg-white/80 p-6 shadow-lg backdrop-blur-sm">
            <h2 class="mb-2 text-base font-semibold text-slate-800">API Base</h2>
            <p class="text-sm leading-relaxed text-slate-500">
              Application endpoints remain mounted at
              <code class="rounded-lg bg-slate-100 px-2 py-0.5 text-xs">/api/v1</code>.
            </p>
          </article>
          <article class="rounded-2xl border border-slate-200/60 bg-white/80 p-6 shadow-lg backdrop-blur-sm">
            <h2 class="mb-2 text-base font-semibold text-slate-800">Database</h2>
            <p class="text-sm leading-relaxed text-slate-500">
              Current local setup uses PostgreSQL on
              <code class="rounded-lg bg-slate-100 px-2 py-0.5 text-xs">127.0.0.1:5432</code>.
            </p>
          </article>
          <article class="rounded-2xl border border-slate-200/60 bg-white/80 p-6 shadow-lg backdrop-blur-sm">
            <h2 class="mb-2 text-base font-semibold text-slate-800">Entry Points</h2>
            <p class="text-sm leading-relaxed text-slate-500">
              Use the module registry and CRM dashboard links above as the first navigation points.
            </p>
          </article>
        </section>
      </main>
    ),
  });
}
