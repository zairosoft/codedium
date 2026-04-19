import { html } from '../../../app/views/components/html';
import { CrmDashboardView } from './crm-contact.view';

function MetricCard({ label, value }: { label: string; value: number }) {
  return (
    <article className="rounded-2xl border border-slate-200/60 bg-white/80 p-5 shadow-lg backdrop-blur-sm">
      <span className="block text-sm text-slate-500">{label}</span>
      <strong className="mt-3 block text-3xl font-extrabold text-teal-700">{value}</strong>
    </article>
  );
}

function ContactRow({ fullName, email, status }: { fullName: string; email: string; status: string }) {
  return (
    <li className="flex items-center justify-between gap-4 border-t border-slate-200 px-0 py-3.5 first:border-t-0 first:pt-0">
      <div>
        <strong className="text-sm font-semibold text-slate-800">{fullName}</strong>
        <div className="mt-0.5 text-sm text-slate-400">{email}</div>
      </div>
      <span className="text-sm font-semibold capitalize text-teal-700">{status}</span>
    </li>
  );
}

export function renderCrmDashboardPage(summary: CrmDashboardView): string {
  return html({
    title: 'CRM Dashboard',
    children: (
      <main className="mx-auto max-w-4xl px-5 py-8">
        <header className="mb-6">
          <h1 className="text-3xl font-extrabold text-slate-900">CRM Dashboard</h1>
          <p className="mt-1 text-sm text-slate-500">Tenant: {summary.tenantId}</p>
        </header>

        <section className="grid grid-cols-1 gap-4 sm:grid-cols-3">
          <MetricCard label="Total contacts" value={summary.metrics.totalContacts} />
          <MetricCard label="Customers" value={summary.metrics.totalCustomers} />
          <MetricCard label="Leads" value={summary.metrics.totalLeads} />
        </section>

        <section className="mt-6 rounded-2xl border border-slate-200/60 bg-white/80 p-6 shadow-lg backdrop-blur-sm">
          <h2 className="mb-4 text-lg font-bold text-slate-800">Recent contacts</h2>
          <ul className="list-none p-0">
            {summary.recentContacts.length > 0
              ? summary.recentContacts.map((c) => (
                  <ContactRow fullName={c.fullName} email={c.email} status={c.status} />
                ))
              : <li className="py-3 text-sm text-slate-400">No recent contacts yet.</li>
            }
          </ul>
        </section>
      </main>
    ),
  });
}
