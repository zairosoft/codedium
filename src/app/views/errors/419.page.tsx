import { createView } from "@/app/views/components/main";

export const render419Page = createView<{}>(({ isLang }) => ({
  title: isLang ? "หน้านี้หมดอายุ | Workless" : "Page expired | Workless",
  bodyClassName:
    "min-h-screen bg-slate-50 text-slate-800 antialiased dark:bg-navy-900",
  children: (
    <main className="grid min-h-screen place-items-center px-4 py-10">
      <section className="w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-xl dark:border-navy-600 dark:bg-navy-800 sm:p-12">
        <p className="text-7xl font-bold tracking-tight text-orange-500">419</p>
        <h1 className="mt-5 text-2xl font-semibold text-slate-800 dark:text-navy-50">
          {isLang ? "หน้านี้หมดอายุแล้ว" : "This page has expired."}
        </h1>
        <p className="mt-3 text-slate-500 dark:text-navy-200">
          {isLang
            ? "กรุณารีเฟรชหน้าเว็บแล้วลองใหม่อีกครั้ง"
            : "Refresh the page and try again."}
        </p>
        <a
          className="btn mt-8 inline-flex justify-center bg-primary px-5 py-2.5 font-medium text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus"
          href=""
        >
          {isLang ? "รีเฟรชหน้าเว็บ" : "Refresh page"}
        </a>
      </section>
    </main>
  ),
}));
