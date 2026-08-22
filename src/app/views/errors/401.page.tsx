import { createView } from "../components/main";

export const render401Page = createView<{}>(({ isLang }) => ({
  title: isLang
    ? "ต้องเข้าสู่ระบบ | Workless"
    : "Authentication required | Workless",
  bodyClassName:
    "min-h-screen bg-slate-50 text-slate-800 antialiased dark:bg-navy-900",
  children: (
    <main className="grid min-h-screen place-items-center px-4 py-10">
      <section className="w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-xl dark:border-navy-600 dark:bg-navy-800 sm:p-12">
        <p className="text-7xl font-bold tracking-tight text-primary dark:text-accent">
          401
        </p>
        <h1 className="mt-5 text-2xl font-semibold text-slate-800 dark:text-navy-50">
          {isLang
            ? "กรุณาเข้าสู่ระบบเพื่อดำเนินการต่อ"
            : "Please sign in to continue."}
        </h1>
        <p className="mt-3 text-slate-500 dark:text-navy-200">
          {isLang
            ? "Session อาจหมดอายุ หรือหน้านี้ต้องยืนยันตัวตนก่อนใช้งาน"
            : "Your session may have expired, or this page requires authentication."}
        </p>
        <div className="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
          <a
            className="btn inline-flex justify-center bg-primary px-5 py-2.5 font-medium text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus"
            href="/auth/login"
          >
            {isLang ? "เข้าสู่ระบบ" : "Sign in"}
          </a>
          <a
            className="btn inline-flex justify-center border border-slate-300 px-5 py-2.5 font-medium text-slate-700 hover:bg-slate-100 dark:border-navy-500 dark:text-navy-100 dark:hover:bg-navy-700"
            href="/"
          >
            {isLang ? "กลับหน้าแรก" : "Back to home"}
          </a>
        </div>
      </section>
    </main>
  ),
}));
