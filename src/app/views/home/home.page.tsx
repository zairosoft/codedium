import { Render } from '../components/main';

export function renderHomePage(): string {
  return Render({
    title: 'Workless Home',
    children: (
      <main className="mx-auto max-w-5xl px-4 py-14">
        <section className="rounded-3xl border border-slate-200/60 bg-white/80 p-8 shadow-xl backdrop-blur-sm">
          <span className="inline-flex rounded-full bg-teal-700/10 px-3 py-1.5 text-xs font-bold uppercase tracking-widest text-teal-800">
            Workless Platform
          </span>
          <h1
            className="mt-5 text-5xl font-extrabold leading-tight tracking-tight text-slate-900"
            style={{ maxWidth: '10ch' }}
          >
            Home page is live.
          </h1>
          <p className="mt-3 max-w-xl text-base leading-relaxed text-slate-500">
            The root route now serves a real HTML page. Your API stays under /api/v1, and the app is connected to the local PostgreSQL instance from ServBay.
          </p>
          <div className="mt-6 flex flex-wrap gap-3">
            <a
              className="inline-flex items-center justify-center rounded-xl bg-gradient-to-br from-teal-600 to-teal-800 px-5 py-3 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5"
              href="/auth/login"
            >
              Sign In
            </a>
            <a
              className="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/70 px-5 py-3 text-sm font-bold text-slate-700 transition hover:-translate-y-0.5"
              href="/api/v1/modules"
            >
              Open system modules
            </a>
            <a
              className="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/70 px-5 py-3 text-sm font-bold text-slate-700 transition hover:-translate-y-0.5"
              href="/api/v1/crm/dashboard/page"
            >
              Open CRM dashboard
            </a>
          </div>
        </section>

        <section className="mt-5 grid grid-cols-1 gap-4 md:grid-cols-3">
          <article className="rounded-2xl border border-slate-200/60 bg-white/80 p-6 shadow-lg backdrop-blur-sm">
            <h2 className="mb-2 text-base font-semibold text-slate-800">API Base</h2>
            <p className="text-sm leading-relaxed text-slate-500">Application endpoints remain mounted at /api/v1.</p>
          </article>
          <article className="rounded-2xl border border-slate-200/60 bg-white/80 p-6 shadow-lg backdrop-blur-sm">
            <h2 className="mb-2 text-base font-semibold text-slate-800">Database</h2>
            <p className="text-sm leading-relaxed text-slate-500">Current local setup uses PostgreSQL on 127.0.0.1:5432.</p>
          </article>
          <article className="rounded-2xl border border-slate-200/60 bg-white/80 p-6 shadow-lg backdrop-blur-sm">
            <h2 className="mb-2 text-base font-semibold text-slate-800">Entry Points</h2>
            <p className="text-sm leading-relaxed text-slate-500">Use the module registry and CRM dashboard links above as the first navigation points.</p>
          </article>
        </section>
      </main>
    ),
  });
}
