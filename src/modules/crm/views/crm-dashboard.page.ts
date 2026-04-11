import { html } from '../../../app/views/components/html';
import { CrmDashboardView } from './crm-contact.view';

function escapeHtml(value: string): string {
  return value
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#39;');
}

function renderMetricCard(label: string, value: number): string {
  return `
    <article class="rounded-2xl border border-slate-200/60 bg-white/80 p-5 shadow-lg backdrop-blur-sm">
      <span class="block text-sm text-slate-500">${escapeHtml(label)}</span>
      <strong class="mt-3 block text-3xl font-extrabold text-teal-700">${value}</strong>
    </article>
  `;
}

export function renderCrmDashboardPage(summary: CrmDashboardView): string {
  const recentContactsMarkup =
    summary.recentContacts.length > 0
      ? summary.recentContacts
          .map(
            (contact) => `
              <li class="flex items-center justify-between gap-4 border-t border-slate-200 px-0 py-3.5 first:border-t-0 first:pt-0">
                <div>
                  <strong class="text-sm font-semibold text-slate-800">${escapeHtml(contact.fullName)}</strong>
                  <div class="mt-0.5 text-sm text-slate-400">${escapeHtml(contact.email)}</div>
                </div>
                <span class="text-sm font-semibold capitalize text-teal-700">${escapeHtml(contact.status)}</span>
              </li>
            `,
          )
          .join('')
      : '<li class="py-3 text-sm text-slate-400">No recent contacts yet.</li>';

  return html({
    title: 'CRM Dashboard',
    body: `
    <main class="mx-auto max-w-4xl px-5 py-8">
      <header class="mb-6">
        <h1 class="text-3xl font-extrabold text-slate-900">CRM Dashboard</h1>
        <p class="mt-1 text-sm text-slate-500">Tenant: ${escapeHtml(summary.tenantId)}</p>
      </header>
      <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        ${renderMetricCard('Total contacts', summary.metrics.totalContacts)}
        ${renderMetricCard('Customers', summary.metrics.totalCustomers)}
        ${renderMetricCard('Leads', summary.metrics.totalLeads)}
      </section>
      <section class="mt-6 rounded-2xl border border-slate-200/60 bg-white/80 p-6 shadow-lg backdrop-blur-sm">
        <h2 class="mb-4 text-lg font-bold text-slate-800">Recent contacts</h2>
        <ul class="list-none p-0">${recentContactsMarkup}</ul>
      </section>
    </main>`,
  });
}
