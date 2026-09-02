import { createView } from "@/app/views/components/main";

export const render403Page = createView<{}>(({ isLang }) => ({
  title: isLang ? "ไม่มีสิทธิ์เข้าถึง | Workless" : "Access denied | Workless",
  bodyClassName:
    "min-h-screen bg-slate-50 text-slate-800 antialiased dark:bg-navy-900",
  children: (
    <main className="grid min-h-screen place-items-center px-4 py-10">
      <section className="w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-xl dark:border-navy-600 dark:bg-navy-800 sm:p-12">
        <p className="text-7xl font-bold tracking-tight text-amber-500">403</p>
        <h1 className="mt-5 text-2xl font-semibold text-slate-800 dark:text-navy-50">
          {isLang
            ? "คุณไม่มีสิทธิ์เข้าถึงหน้านี้"
            : "You do not have permission to view this page."}
        </h1>
        <p className="mt-3 text-slate-500 dark:text-navy-200">
          {isLang
            ? "ติดต่อผู้ดูแลระบบหากคิดว่าควรมีสิทธิ์เข้าถึง"
            : "Contact an administrator if you believe you should have access."}
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
