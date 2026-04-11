export function renderHomePage(): string {
  return `<!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>Workless Home</title>
      <style>
        :root {
          color-scheme: light;
          --bg: #f6f6f1;
          --panel: rgba(255, 255, 255, 0.82);
          --text: #1f2937;
          --muted: #5f6b7a;
          --border: rgba(148, 163, 184, 0.28);
          --accent: #0f766e;
          --accent-strong: #134e4a;
          --shadow: 0 18px 60px rgba(15, 23, 42, 0.12);
        }
        * { box-sizing: border-box; }
        body {
          margin: 0;
          min-height: 100vh;
          font-family: "Segoe UI", "SF Pro Display", -apple-system, BlinkMacSystemFont, sans-serif;
          color: var(--text);
          background:
            radial-gradient(circle at top left, rgba(20, 184, 166, 0.18), transparent 28%),
            radial-gradient(circle at top right, rgba(245, 158, 11, 0.16), transparent 24%),
            linear-gradient(180deg, #fcfcf9 0%, var(--bg) 100%);
        }
        main {
          width: min(1120px, calc(100% - 32px));
          margin: 0 auto;
          padding: 56px 0 72px;
        }
        .hero,
        .card {
          border: 1px solid var(--border);
          background: var(--panel);
          backdrop-filter: blur(10px);
          box-shadow: var(--shadow);
        }
        .hero {
          border-radius: 28px;
          padding: 32px;
        }
        .eyebrow {
          display: inline-flex;
          border-radius: 999px;
          padding: 8px 12px;
          font-size: 12px;
          font-weight: 700;
          letter-spacing: 0.18em;
          text-transform: uppercase;
          color: var(--accent-strong);
          background: rgba(15, 118, 110, 0.12);
        }
        h1 {
          margin: 18px 0 12px;
          font-size: clamp(2.5rem, 6vw, 5rem);
          line-height: 0.95;
          letter-spacing: -0.04em;
          max-width: 10ch;
        }
        .lead {
          max-width: 58ch;
          margin: 0;
          color: var(--muted);
          font-size: 1.05rem;
          line-height: 1.75;
        }
        .actions {
          display: flex;
          flex-wrap: wrap;
          gap: 12px;
          margin-top: 24px;
        }
        .button {
          display: inline-flex;
          align-items: center;
          justify-content: center;
          border-radius: 14px;
          padding: 14px 18px;
          text-decoration: none;
          font-weight: 700;
          transition: transform 140ms ease, opacity 140ms ease, border-color 140ms ease;
        }
        .button:hover {
          transform: translateY(-1px);
        }
        .button-primary {
          color: white;
          background: linear-gradient(135deg, var(--accent) 0%, var(--accent-strong) 100%);
        }
        .button-secondary {
          color: var(--text);
          border: 1px solid var(--border);
          background: rgba(255, 255, 255, 0.72);
        }
        .grid {
          display: grid;
          grid-template-columns: repeat(3, minmax(0, 1fr));
          gap: 16px;
          margin-top: 18px;
        }
        .card {
          border-radius: 22px;
          padding: 22px;
        }
        .card h2 {
          margin: 0 0 10px;
          font-size: 1rem;
        }
        .card p,
        .card code {
          color: var(--muted);
          line-height: 1.7;
        }
        code {
          display: inline-block;
          border-radius: 10px;
          padding: 4px 8px;
          background: rgba(15, 23, 42, 0.06);
          font-size: 0.92em;
        }
        @media (max-width: 820px) {
          main {
            padding-top: 24px;
          }
          .hero {
            padding: 24px;
          }
          .grid {
            grid-template-columns: 1fr;
          }
        }
      </style>
    </head>
    <body>
      <main>
        <section class="hero">
          <span class="eyebrow">Workless Platform</span>
          <h1>Home page is live.</h1>
          <p class="lead">
            The root route now serves a real HTML page. Your API stays under
            <code>/api/v1</code>, and the app is connected to the local PostgreSQL instance from ServBay.
          </p>
          <div class="actions">
            <a class="button button-primary" href="/api/v1/system/modules">Open system modules</a>
            <a class="button button-secondary" href="/api/v1/crm/dashboard/page">Open CRM dashboard</a>
          </div>
        </section>

        <section class="grid">
          <article class="card">
            <h2>API Base</h2>
            <p>Application endpoints remain mounted at <code>/api/v1</code>.</p>
          </article>
          <article class="card">
            <h2>Database</h2>
            <p>Current local setup uses PostgreSQL on <code>127.0.0.1:5432</code>.</p>
          </article>
          <article class="card">
            <h2>Entry Points</h2>
            <p>Use the module registry and CRM dashboard links above as the first navigation points.</p>
          </article>
        </section>
      </main>
    </body>
  </html>`;
}
