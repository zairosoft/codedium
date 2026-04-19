import * as Html from '@kitajs/html';

type LoginPageOptions = {
  error?: string;
  email?: string;
};

const LOGO_URL = 'https://www.zairosoft.com/assets/2025/12/logo.webp';

const GOOGLE_SVG = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/><path fill="none" d="M0 0h48v48H0z"/></svg>`;

export function renderLoginPage(options: LoginPageOptions = {}): string {
  const page = (
    <html lang="en" class="dark">
      <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta
          name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
        />
        <title>Workless - Sign In</title>
        <link rel="stylesheet" href="/assets/css/tailwindcss.css" />
        <link rel="preconnect" href="https://fonts.googleapis.com/" />
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="anonymous" />
        <link
          href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet"
        />
      </head>
      <body class="is-header-blur">
        <div id="root" class="min-h-100vh flex grow bg-slate-50 dark:bg-navy-900">
          <main class="grid w-full grow grid-cols-1 place-items-center">
            <div class="w-full max-w-[26rem] p-4 sm:px-5">
              <div class="text-center">
                <img
                  src={LOGO_URL}
                  alt="Workless"
                  class="mx-auto object-contain"
                  width="50"
                  height="50"
                />
                <div class="mt-4">
                  <h2 class="text-2xl font-semibold text-slate-600 dark:text-navy-100">
                    Welcome Back
                  </h2>
                  <p class="text-slate-400 dark:text-navy-300">
                    Please sign in to continue
                  </p>
                </div>
              </div>
              <div class="card mt-5 rounded-lg p-5 lg:p-7">
                <label class="block">
                  <span>Username:</span>
                  <span class="relative mt-1.5 flex">
                    <input
                      id="login-email"
                      class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:z-10 hover:border-slate-400 focus:z-10 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                      placeholder="Enter Username"
                      type="text"
                      autocomplete="username"
                      value={options.email ?? ''}
                    />
                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="size-5 transition-colors duration-200"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.5"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                        />
                      </svg>
                    </span>
                  </span>
                </label>
                <label class="mt-4 block">
                  <span>Password:</span>
                  <span class="relative mt-1.5 flex">
                    <input
                      id="login-password"
                      class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:z-10 hover:border-slate-400 focus:z-10 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                      placeholder="Enter Password"
                      type="password"
                      autocomplete="current-password"
                    />
                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="size-5 transition-colors duration-200"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.5"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                        />
                      </svg>
                    </span>
                  </span>
                </label>
                <div class="mt-4 flex items-center justify-between space-x-2">
                  <label class="inline-flex items-center space-x-2">
                    <input
                      class="form-checkbox is-basic size-5 rounded-sm border-slate-400/70 checked:border-primary checked:bg-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:border-accent dark:checked:bg-accent dark:hover:border-accent dark:focus:border-accent"
                      type="checkbox"
                      id="remember-me"
                    />
                    <span class="line-clamp-1">Remember me</span>
                  </label>
                  <a
                    href="#"
                    class="text-xs text-slate-400 transition-colors line-clamp-1 hover:text-slate-800 focus:text-slate-800 dark:text-navy-300 dark:hover:text-navy-100 dark:focus:text-navy-100"
                  >
                    Forgot Password?
                  </a>
                </div>
                <button
                  id="login-submit"
                  class="btn mt-5 w-full bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90"
                  type="button"
                >
                  Sign In
                </button>
                {options.error && (
                  <div
                    id="login-error"
                    role="alert"
                    class="mt-4 rounded-lg border border-red-400/30 bg-red-400/10 px-3 py-2 text-xs-plus text-red-400"
                    safe
                  >
                    {options.error}
                  </div>
                )}
                {!options.error && (
                  <div
                    id="login-error"
                    role="alert"
                    class="mt-4 rounded-lg border border-red-400/30 bg-red-400/10 px-3 py-2 text-xs-plus text-red-400"
                    style="display:none"
                  />
                )}
                <div class="mt-4 text-center text-xs-plus">
                  <p class="line-clamp-1">
                    <span>Dont have Account?</span>{' '}
                    <a
                      class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                      href="#"
                    >
                      Create account
                    </a>
                  </p>
                </div>
                <div class="my-7 flex items-center space-x-3">
                  <div class="h-px flex-1 bg-slate-200 dark:bg-navy-500"></div>
                  <p>OR</p>
                  <div class="h-px flex-1 bg-slate-200 dark:bg-navy-500"></div>
                </div>
                <div class="flex">
                  <button
                    class="btn w-full space-x-3 border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90"
                    type="button"
                  >
                    <span class="size-5.5">{GOOGLE_SVG as 'safe'}</span>
                    <span>Google</span>
                  </button>
                </div>
              </div>
              <div class="mt-8 flex justify-center text-xs text-slate-400 dark:text-navy-300">
                <a href="#">Privacy Notice</a>
                <div class="mx-3 my-1 w-px bg-slate-200 dark:bg-navy-500"></div>
                <a href="#">Term of service</a>
              </div>
            </div>
          </main>
        </div>

        <script>{`
          (function() {
            var btn = document.getElementById('login-submit');
            var errEl = document.getElementById('login-error');

            function showError(msg) {
              errEl.textContent = msg;
              errEl.style.display = 'block';
            }

            btn.addEventListener('click', async function() {
              var email = document.getElementById('login-email').value.trim();
              errEl.style.display = 'none';

              if (!email) {
                showError('Please enter your email address.');
                document.getElementById('login-email').focus();
                return;
              }

              btn.disabled = true;
              btn.textContent = 'Signing in...';

              try {
                var res = await fetch('/api/v1/auth/login', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify({ email: email })
                });
                var data = await res.json();

                if (!res.ok) {
                  showError(data.message || 'Login failed. Please try again.');
                  return;
                }

                sessionStorage.setItem('workless_token', data.accessToken);
                window.location.href = '/';
              } catch (e) {
                showError('Network error. Please check your connection.');
              } finally {
                btn.disabled = false;
                btn.textContent = 'Sign In';
              }
            });
          })();
        ` as 'safe'}</script>
      </body>
    </html>
  ) as unknown as string;

  return '<!DOCTYPE html>' + (page as unknown as string)
    .replace(/\n\s*/g, '')
    .replace(/>\s+</g, '><')
    .replace(/\s{2,}/g, ' ')
    .trim();
}
