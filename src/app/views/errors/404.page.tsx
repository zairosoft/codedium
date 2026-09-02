import { createView } from "@/app/views/components/main";

export const render404Page = createView<{}>(({ isLang }) => ({
  title: isLang ? "ไม่พบหน้านี้ | Workless" : "Page not found | Workless",
  bodyClassName:
    "min-h-screen bg-slate-50 text-slate-800 antialiased dark:bg-navy-900",
  children: (
    <main className="grid min-h-screen place-items-center px-4 py-10">
      <section className="w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-xl dark:border-navy-600 dark:bg-navy-800 sm:p-12">
        <p className="text-7xl font-bold tracking-tight text-primary dark:text-accent">
          404
        </p>
        <h1 className="mt-5 text-2xl font-semibold text-slate-800 dark:text-navy-50">
          {isLang
            ? "ไม่พบหน้าที่คุณกำลังค้นหา"
            : "We could not find that page."}
        </h1>
        <p className="mt-3 text-slate-500 dark:text-navy-200">
          {isLang
            ? "ลิงก์อาจไม่ถูกต้อง หรือหน้านี้อาจถูกย้ายไปแล้ว"
            : "The link may be incorrect or the page may have moved."}
        </p>
        <a
          className="btn mt-8 inline-flex justify-center bg-primary px-5 py-2.5 font-medium text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus"
          href="/"
        >
          {isLang ? "กลับหน้าแรก" : "Back to home"}
        </a>
      </section>
    </main>
  ),
}));
