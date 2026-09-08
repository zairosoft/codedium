import { renderMainLayoutView } from '@/app/views/components/layouts/layout';

export function renderCompaniesPage(): string {
  const script = `
    (() => {
      const apiUrl = '/api/v1/platform/companies';
      const state = { companies: [], page: 1, limit: 20, total: 0, search: '' };

      function token() {
        return sessionStorage.getItem('workless_token');
      }

      function showMessage(message, isError) {
        const element = document.querySelector('[data-company-message]');
        if (!element) return;
        element.textContent = message || '';
        element.className = isError
          ? 'mb-4 rounded-lg bg-error/10 px-4 py-3 text-sm text-error'
          : 'mb-4 rounded-lg bg-success/10 px-4 py-3 text-sm text-success';
        element.hidden = !message;
      }

      async function request(path, options) {
        const accessToken = token();
        if (!accessToken) {
          window.location.href = '/auth/login';
          throw new Error('Authentication is required.');
        }

        const response = await fetch(path, {
          ...options,
          headers: {
            Authorization: 'Bearer ' + accessToken,
            ...(options && options.body ? { 'Content-Type': 'application/json' } : {}),
            ...((options && options.headers) || {}),
          },
        });
        const payload = await response.json().catch(() => ({}));

        if (response.status === 401) {
          sessionStorage.removeItem('workless_token');
          window.location.href = '/auth/login';
          throw new Error('Your session has expired.');
        }
        if (!response.ok) {
          const message = Array.isArray(payload.message)
            ? payload.message.join(', ')
            : payload.message || 'Unable to complete the request.';
          throw new Error(message);
        }

        return payload;
      }

      function cell(text, className) {
        const td = document.createElement('td');
        td.className = className || 'whitespace-nowrap px-4 py-3';
        td.textContent = text;
        return td;
      }

      function renderRows() {
        const body = document.querySelector('[data-company-rows]');
        const empty = document.querySelector('[data-company-empty]');
        if (!body || !empty) return;
        body.replaceChildren();
        empty.hidden = state.companies.length > 0;

        state.companies.forEach((company) => {
          const row = document.createElement('tr');
          row.className = 'border-t border-slate-200 transition-colors hover:bg-slate-50 dark:border-navy-500 dark:hover:bg-navy-700/40';

          const identity = document.createElement('td');
          identity.className = 'px-4 py-3';
          const wrap = document.createElement('div');
          wrap.className = 'flex items-center gap-3';
          const avatar = document.createElement('span');
          avatar.className = 'flex size-10 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-primary/10 font-semibold text-primary dark:bg-primary/15';
          if (company.logo) {
            const image = document.createElement('img');
            image.src = company.logo;
            image.alt = '';
            image.className = 'size-full object-cover';
            avatar.appendChild(image);
          } else {
            avatar.textContent = company.name.slice(0, 2).toUpperCase();
          }
          const labels = document.createElement('div');
          labels.className = 'min-w-0';
          const name = document.createElement('p');
          name.className = 'truncate font-medium text-slate-800 dark:text-navy-50';
          name.textContent = company.name;
          const description = document.createElement('p');
          description.className = 'mt-0.5 max-w-sm truncate text-xs text-slate-500 dark:text-navy-300';
          description.textContent = company.description || 'No description';
          labels.append(name, description);
          wrap.append(avatar, labels);
          identity.appendChild(wrap);
          row.appendChild(identity);
          row.appendChild(cell(company.code, 'whitespace-nowrap px-4 py-3 font-mono text-xs text-slate-600 dark:text-navy-200'));

          const status = document.createElement('td');
          status.className = 'whitespace-nowrap px-4 py-3';
          const badge = document.createElement('span');
          badge.className = company.isActive
            ? 'inline-flex rounded-full bg-success/10 px-2.5 py-1 text-xs font-medium text-success'
            : 'inline-flex rounded-full bg-slate-200 px-2.5 py-1 text-xs font-medium text-slate-600 dark:bg-navy-600 dark:text-navy-100';
          badge.textContent = company.isActive ? 'Active' : 'Inactive';
          status.appendChild(badge);
          row.appendChild(status);
          row.appendChild(cell(new Date(company.updatedAt).toLocaleDateString(), 'whitespace-nowrap px-4 py-3 text-sm text-slate-500 dark:text-navy-300'));

          const actions = document.createElement('td');
          actions.className = 'whitespace-nowrap px-4 py-3 text-right';
          const edit = document.createElement('button');
          edit.type = 'button';
          edit.className = 'rounded-lg px-3 py-1.5 text-sm font-medium text-primary transition-colors hover:bg-primary/10';
          edit.textContent = 'Edit';
          edit.addEventListener('click', () => openEditor(company));
          const remove = document.createElement('button');
          remove.type = 'button';
          remove.className = 'ml-1 rounded-lg px-3 py-1.5 text-sm font-medium text-error transition-colors hover:bg-error/10';
          remove.textContent = 'Delete';
          remove.addEventListener('click', async () => {
            if (!window.confirm('Delete company "' + company.name + '"?')) return;

            try {
              await request(apiUrl + '/' + company.id, { method: 'DELETE' });
              await loadCompanies(false);
              showMessage('Company deleted.', false);
            } catch (error) {
              showMessage(error.message, true);
            }
          });
          actions.append(edit, remove);
          row.appendChild(actions);
          body.appendChild(row);
        });

        const from = state.total === 0 ? 0 : (state.page - 1) * state.limit + 1;
        const to = Math.min(state.page * state.limit, state.total);
        document.querySelector('[data-company-range]').textContent = from + '-' + to + ' of ' + state.total;
        document.querySelector('[data-company-prev]').disabled = state.page <= 1;
        document.querySelector('[data-company-next]').disabled = to >= state.total;
      }

      async function loadCompanies(clearMessage = true) {
        if (clearMessage) showMessage('', false);
        const params = new URLSearchParams({
          page: String(state.page),
          limit: String(state.limit),
        });
        if (state.search) params.set('search', state.search);

        try {
          const result = await request(apiUrl + '?' + params.toString());
          state.companies = result.data || [];
          state.total = result.meta ? result.meta.total : 0;
          renderRows();
        } catch (error) {
          showMessage(error.message, true);
        }
      }

      function openEditor(company) {
        const dialog = document.querySelector('[data-company-dialog]');
        const form = document.querySelector('[data-company-form]');
        form.reset();
        form.elements.id.value = company ? company.id : '';
        form.elements.name.value = company ? company.name : '';
        form.elements.code.value = company ? company.code : '';
        form.elements.description.value = company && company.description ? company.description : '';
        form.elements.logo.value = company && company.logo ? company.logo : '';
        form.elements.isActive.checked = company ? company.isActive : true;
        document.querySelector('[data-company-dialog-title]').textContent = company ? 'Edit company' : 'Create company';
        dialog.showModal();
      }

      document.addEventListener('DOMContentLoaded', () => {
        if (!token()) {
          window.location.href = '/auth/login';
          return;
        }

        document.querySelector('[data-company-create]').addEventListener('click', () => openEditor(null));
        document.querySelectorAll('[data-company-cancel]').forEach((button) => {
          button.addEventListener('click', () => {
            document.querySelector('[data-company-dialog]').close();
          });
        });
        document.querySelector('[data-company-search-form]').addEventListener('submit', (event) => {
          event.preventDefault();
          state.search = event.currentTarget.elements.search.value.trim();
          state.page = 1;
          loadCompanies();
        });
        document.querySelector('[data-company-prev]').addEventListener('click', () => {
          if (state.page > 1) {
            state.page -= 1;
            loadCompanies();
          }
        });
        document.querySelector('[data-company-next]').addEventListener('click', () => {
          if (state.page * state.limit < state.total) {
            state.page += 1;
            loadCompanies();
          }
        });
        document.querySelector('[data-company-form]').addEventListener('submit', async (event) => {
          event.preventDefault();
          const form = event.currentTarget;
          const id = form.elements.id.value;
          const payload = {
            name: form.elements.name.value.trim(),
            code: form.elements.code.value.trim().toLowerCase(),
            description: form.elements.description.value.trim(),
            logo: form.elements.logo.value.trim(),
            isActive: form.elements.isActive.checked,
          };

          try {
            await request(id ? apiUrl + '/' + id : apiUrl, {
              method: id ? 'PATCH' : 'POST',
              body: JSON.stringify(payload),
            });
            document.querySelector('[data-company-dialog]').close();
            await loadCompanies(false);
            showMessage(id ? 'Company updated.' : 'Company created.', false);
          } catch (error) {
            showMessage(error.message, true);
          }
        });

        loadCompanies();
      });
    })();
  `;

  const inputClass =
    'form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-slate-800 outline-hidden placeholder:text-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:focus:border-primary';

  return renderMainLayoutView({
    title: 'Companies',
    content: (
      <>
        <div data-company-message hidden />

        <section className="rounded-lg border border-slate-200 bg-white shadow-sm dark:border-navy-500 dark:bg-navy-700">
          <div className="flex flex-col gap-4 border-b border-slate-200 p-4 dark:border-navy-500 sm:flex-row sm:items-center sm:justify-between">
            <form data-company-search-form className="flex w-full max-w-md gap-2">
              <input name="search" type="search" placeholder="Search name or code" className="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm outline-hidden focus:border-primary dark:border-navy-450 dark:text-navy-100" />
              <button type="submit" className="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-100 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-600">Search</button>
            </form>
            <button data-company-create type="button" className="shrink-0 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-primary-focus">Add company</button>
          </div>

          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm">
              <thead className="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-navy-800/50 dark:text-navy-300">
                <tr>
                  <th className="px-4 py-3 font-semibold">Company</th>
                  <th className="px-4 py-3 font-semibold">Code</th>
                  <th className="px-4 py-3 font-semibold">Status</th>
                  <th className="px-4 py-3 font-semibold">Updated</th>
                  <th className="px-4 py-3 text-right font-semibold">Action</th>
                </tr>
              </thead>
              <tbody data-company-rows />
            </table>
            <div data-company-empty className="px-4 py-12 text-center text-sm text-slate-500 dark:text-navy-300">No companies found.</div>
          </div>

          <div className="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm dark:border-navy-500">
            <span data-company-range className="text-slate-500 dark:text-navy-300">0-0 of 0</span>
            <div className="flex gap-2">
              <button data-company-prev type="button" className="rounded-lg border border-slate-300 px-3 py-1.5 font-medium text-slate-600 disabled:cursor-not-allowed disabled:opacity-40 dark:border-navy-450 dark:text-navy-100">Previous</button>
              <button data-company-next type="button" className="rounded-lg border border-slate-300 px-3 py-1.5 font-medium text-slate-600 disabled:cursor-not-allowed disabled:opacity-40 dark:border-navy-450 dark:text-navy-100">Next</button>
            </div>
          </div>
        </section>

        <dialog data-company-dialog className="m-auto w-[calc(100%-2rem)] max-w-xl rounded-xl border border-slate-200 bg-white p-0 text-slate-700 shadow-2xl backdrop:bg-slate-900/50 dark:border-navy-500 dark:bg-navy-700 dark:text-navy-100">
          <form data-company-form className="p-5 sm:p-6">
            <input type="hidden" name="id" />
            <div className="flex items-center justify-between">
              <h2 data-company-dialog-title className="text-lg font-semibold text-slate-800 dark:text-navy-50">Create company</h2>
              <button data-company-cancel type="button" className="flex size-8 items-center justify-center rounded-full text-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-navy-600 dark:hover:text-navy-50" aria-label="Close">×</button>
            </div>

            <div className="mt-5 grid gap-4 sm:grid-cols-2">
              <label className="block text-sm font-medium">Name<input required name="name" maxLength={160} className={inputClass} /></label>
              <label className="block text-sm font-medium">Code<input required name="code" maxLength={80} pattern="[a-z0-9][a-z0-9_-]*" className={inputClass} /></label>
              <label className="block text-sm font-medium sm:col-span-2">Logo URL<input name="logo" maxLength={500} className={inputClass} /></label>
              <label className="block text-sm font-medium sm:col-span-2">Description<textarea name="description" rows={3} className={inputClass} /></label>
              <label className="flex items-center gap-2 text-sm font-medium sm:col-span-2">
                <input name="isActive" type="checkbox" className="form-checkbox size-5 rounded border-slate-400 text-primary focus:ring-primary dark:border-navy-450" />
                Active company
              </label>
            </div>

            <div className="mt-6 flex justify-end gap-3">
              <button data-company-cancel type="button" className="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:border-navy-450 dark:text-navy-100 dark:hover:bg-navy-600">Cancel</button>
              <button type="submit" className="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-primary-focus">Save company</button>
            </div>
          </form>
        </dialog>

        <script dangerouslySetInnerHTML={{ __html: script }} />
      </>
    ),
  });
}
