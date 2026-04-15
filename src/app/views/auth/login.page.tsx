import * as Html from '@kitajs/html';

type LoginPageOptions = {
  error?: string;
  email?: string;
};

const LOGO_SVG = `<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="none" viewBox="0 0 32 32">
  <path fill="#1DA8BB" d="M17.298 9.88a9.023 9.023 0 011.046 6.914 7.052 7.052 0 002.858-.983 7.096 7.096 0 10-7.39-12.116c-.902.55-1.63 1.27-2.182 2.091a9.024 9.024 0 015.668 4.095z"/>
  <path fill="#F09116" d="M9.88 14.73a9.021 9.021 0 016.915-1.046 7.057 7.057 0 00-.984-2.858 7.096 7.096 0 00-12.116 7.39c.55.901 1.27 1.63 2.092 2.183a9.021 9.021 0 014.094-5.67z"/>
  <path fill="#9333EA" d="M18.2 28.305a7.06 7.06 0 002.182-2.092 9.02 9.02 0 01-5.669-4.094 9.02 9.02 0 01-1.045-6.913 7.058 7.058 0 00-2.859.983 7.096 7.096 0 107.39 12.116z"/>
  <path fill="#12CEB7" d="M22.12 17.315a9.023 9.023 0 01-6.914 1.046 7.05 7.05 0 00.983 2.858 7.096 7.096 0 0012.116-7.39 7.05 7.05 0 00-2.091-2.182 9.024 9.024 0 01-4.095 5.668z"/>
</svg>`;

const GOOGLE_SVG = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/><path fill="none" d="M0 0h48v48H0z"/></svg>`;


export function renderLoginPage(options: LoginPageOptions = {}): string {
  const page = (
    <html lang="en">
      <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
        <title>Workless — Sign In</title>
        <link rel="stylesheet" href="/assets/css/app.css" />
        <link rel="stylesheet" href="/assets/css/login.css" />
        <link rel="preconnect" href="https://fonts.googleapis.com/" />
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="anonymous" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
      </head>
      <body>
        <div class="page-wrap">
          <div class="page-inner">

            {/* Logo + Heading */}
            <div class="heading">
              {LOGO_SVG as 'safe'}
              <div class="mt-4">
                <h1>Welcome Back</h1>
                <p>Please sign in to continue</p>
              </div>
            </div>

            {/* Card */}
            <div class="card mt-5">

              {/* Email */}
              <label class="field">
                <span>Email:</span>
                <div class="input-wrap">
                  <input
                    id="login-email"
                    class="form-input"
                    placeholder="Enter Email"
                    type="email"
                    autocomplete="email"
                    value={options.email ?? ''}
                  />
                  <span class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" style="width:1.25rem;height:1.25rem" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                  </span>
                </div>
              </label>

              {/* Password */}
              <label class="field">
                <span>Password:</span>
                <div class="input-wrap">
                  <input
                    id="login-password"
                    class="form-input"
                    placeholder="Enter Password"
                    type="password"
                    autocomplete="current-password"
                  />
                  <span class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                  </span>
                </div>
              </label>

              {/* Remember me + Forgot */}
              <div class="check-row">
                <label class="check-label">
                  <input class="form-checkbox" type="checkbox" id="remember-me" />
                  <span class="line-clamp-1">Remember me</span>
                </label>
                <a href="#" class="forgot-link">Forgot Password?</a>
              </div>

              {/* Submit */}
              <button id="login-submit" class="btn btn-primary" type="button">
                Sign In
              </button>

              {/* Error */}
              {options.error && (
                <div id="login-error" role="alert" style="margin-top:0.75rem;padding:0.5rem 0.75rem;background:#fef2f2;border:1px solid #fecaca;border-radius:0.375rem;color:#dc2626;font-size:0.8125rem" safe>
                  {options.error}
                </div>
              )}
              {!options.error && (
                <div id="login-error" role="alert" style="margin-top:0.75rem;padding:0.5rem 0.75rem;background:#fef2f2;border:1px solid #fecaca;border-radius:0.375rem;color:#dc2626;font-size:0.8125rem;display:none" />
              )}

              {/* Sign up link */}
              <div class="signup-row">
                <span>Dont have Account? </span>
                <a class="link-primary" href="#">Create account</a>
              </div>

              {/* OR */}
              <div class="or-divider">
                <div class="or-line" />
                <p>OR</p>
                <div class="or-line" />
              </div>

              {/* Social */}
              <div class="social-row">
                <button class="btn btn-outline" type="button">
                  {GOOGLE_SVG as 'safe'}
                  <span>Google</span>
                </button>
                <button class="btn btn-outline" type="button">
                  <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
                  </svg>
                  <span>Github</span>
                </button>
              </div>
            </div>

            {/* Footer */}
            <div class="footer-links">
              <a href="#">Privacy Notice</a>
              <div class="footer-divider" />
              <a href="#">Term of service</a>
            </div>

          </div>
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
              } catch(e) {
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
