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
    <article class="metric-card">
      <span class="metric-label">${escapeHtml(label)}</span>
      <strong class="metric-value">${value}</strong>
    </article>
  `;
}

export function renderCrmDashboardPage(summary: CrmDashboardView): string {
  const recentContactsMarkup =
    summary.recentContacts.length > 0
      ? summary.recentContacts
          .map(
            (contact) => `
              <li class="contact-row">
                <div>
                  <strong>${escapeHtml(contact.fullName)}</strong>
                  <div class="contact-meta">${escapeHtml(contact.email)}</div>
                </div>
                <span class="contact-status">${escapeHtml(contact.status)}</span>
              </li>
            `,
          )
          .join('')
      : '<li class="empty-state">No recent contacts yet.</li>';

  return `<!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>CRM Dashboard</title>
      <style>
        :root {
          color-scheme: light;
          --bg: #f5f3ef;
          --panel: #fffdf9;
          --text: #1f2937;
          --muted: #6b7280;
          --border: #e5ded3;
          --accent: #155e75;
        }
        * { box-sizing: border-box; }
        body {
          margin: 0;
          font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
          background: linear-gradient(180deg, #efe7dc 0%, var(--bg) 100%);
          color: var(--text);
        }
        main {
          max-width: 980px;
          margin: 0 auto;
          padding: 32px 20px 48px;
        }
        header { margin-bottom: 24px; }
        h1 { margin: 0 0 8px; font-size: 2rem; }
        p { margin: 0; color: var(--muted); }
        .metrics {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
          gap: 16px;
          margin: 24px 0;
        }
        .metric-card,
        .panel {
          background: var(--panel);
          border: 1px solid var(--border);
          border-radius: 18px;
          padding: 20px;
          box-shadow: 0 10px 30px rgba(31, 41, 55, 0.06);
        }
        .metric-label {
          display: block;
          margin-bottom: 12px;
          color: var(--muted);
          font-size: 0.95rem;
        }
        .metric-value {
          font-size: 2rem;
          color: var(--accent);
        }
        ul {
          list-style: none;
          padding: 0;
          margin: 0;
        }
        .contact-row,
        .empty-state {
          display: flex;
          justify-content: space-between;
          align-items: center;
          padding: 14px 0;
          border-top: 1px solid var(--border);
          gap: 16px;
        }
        .contact-row:first-child,
        .empty-state:first-child {
          border-top: 0;
          padding-top: 0;
        }
        .contact-meta {
          color: var(--muted);
          font-size: 0.95rem;
          margin-top: 4px;
        }
        .contact-status {
          text-transform: capitalize;
          color: var(--accent);
          font-weight: 600;
        }
        .empty-state {
          color: var(--muted);
          justify-content: flex-start;
        }
      </style>
    </head>
    <body>
      <main>
        <header>
          <h1>CRM Dashboard</h1>
          <p>Tenant: ${escapeHtml(summary.tenantId)}</p>
        </header>
        <section class="metrics">
          ${renderMetricCard('Total contacts', summary.metrics.totalContacts)}
          ${renderMetricCard('Customers', summary.metrics.totalCustomers)}
          ${renderMetricCard('Leads', summary.metrics.totalLeads)}
        </section>
        <section class="panel">
          <h2>Recent contacts</h2>
          <ul>${recentContactsMarkup}</ul>
        </section>
      </main>
    </body>
  </html>`;
}
