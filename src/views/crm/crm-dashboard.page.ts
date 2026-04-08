type CrmDashboardPageModel = {
  tenantId: string;
  metrics: {
    totalContacts: number;
    totalCustomers: number;
    totalLeads: number;
  };
  recentContacts: Array<{
    fullName: string;
    email: string;
    status: string;
  }>;
};

export function renderCrmDashboardPage(model: CrmDashboardPageModel): string {
  const recentRows = model.recentContacts
    .map(
      (contact) => `
        <tr>
          <td>${escapeHtml(contact.fullName)}</td>
          <td>${escapeHtml(contact.email)}</td>
          <td>${escapeHtml(contact.status)}</td>
        </tr>`,
    )
    .join('');

  return `<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CRM Dashboard</title>
    <style>
      :root {
        color-scheme: light;
        --bg: #f4f1ea;
        --panel: #fffaf2;
        --text: #1f2937;
        --muted: #6b7280;
        --accent: #a34a28;
        --border: #e5d6c4;
      }
      body {
        margin: 0;
        font-family: Georgia, "Times New Roman", serif;
        background: radial-gradient(circle at top, #fff7eb 0, var(--bg) 55%, #ece4d8 100%);
        color: var(--text);
      }
      main {
        max-width: 1080px;
        margin: 0 auto;
        padding: 48px 24px 64px;
      }
      h1 {
        margin: 0 0 8px;
        font-size: 2.4rem;
      }
      p {
        color: var(--muted);
      }
      .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin: 32px 0;
      }
      .card, table {
        background: rgba(255, 250, 242, 0.88);
        border: 1px solid var(--border);
        border-radius: 18px;
        box-shadow: 0 14px 32px rgba(80, 40, 18, 0.08);
      }
      .card {
        padding: 20px;
      }
      .metric {
        display: block;
        font-size: 2rem;
        color: var(--accent);
        margin-top: 8px;
      }
      table {
        width: 100%;
        border-collapse: collapse;
        overflow: hidden;
      }
      th, td {
        padding: 14px 16px;
        text-align: left;
        border-bottom: 1px solid var(--border);
      }
      th {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted);
      }
      tr:last-child td {
        border-bottom: none;
      }
    </style>
  </head>
  <body>
    <main>
      <p>Tenant: ${escapeHtml(model.tenantId)}</p>
      <h1>CRM Operations Dashboard</h1>
      <p>Public dashboard views can be cached at the reverse proxy for 60 seconds.</p>
      <section class="grid">
        <article class="card">
          <small>Total contacts</small>
          <strong class="metric">${model.metrics.totalContacts}</strong>
        </article>
        <article class="card">
          <small>Customers</small>
          <strong class="metric">${model.metrics.totalCustomers}</strong>
        </article>
        <article class="card">
          <small>Leads</small>
          <strong class="metric">${model.metrics.totalLeads}</strong>
        </article>
      </section>
      <table>
        <thead>
          <tr>
            <th>Contact</th>
            <th>Email</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>${recentRows}</tbody>
      </table>
    </main>
  </body>
</html>`;
}

function escapeHtml(value: string): string {
  return value
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

