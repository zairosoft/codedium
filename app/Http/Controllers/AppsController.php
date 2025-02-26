<?php

namespace App\Http\Controllers;

use Nwidart\Modules\Facades\Module;

class AppsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'lock']);
        $this->middleware('permission:setting view', ['only' => ['general', 'company', 'system']]);
        $this->middleware('permission:setting create', ['only' => ['create', 'store']]);
        $this->middleware('permission:setting update', ['only' => ['update', 'edit']]);
        $this->middleware('permission:setting delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $modules = Module::allEnabled();
        $html = '';
        foreach ($modules as $module) {
            $name = strtolower($module->getName());

            if (config($name . ".installable") == true) {
                $installable = '<a href="javascript:;" class="btn btn-outline-primary w-1/2">ติดแล้ว</a>';
            } else {
                $installable = '<a href="javascript:;" class="btn btn-outline-primary w-1/2">ติดตั้ง</a>';
            }

            $html .= '<div class="h-full">' .
                '<div class="border border-gray-500/20 rounded-md shadow-[rgb(31_45_61_/_10%)_0px_2px_10px_1px] dark:shadow-[0_2px_11px_0_rgb(6_8_24_/_39%)] p-6 pt-12 mt-8 relative">' .
                '<div class="bg-dark absolute text-white-light left-6 -top-8 w-16 h-16 rounded-md flex items-center justify-center mb-5 mx-auto">' .
                config($name . ".icon")
                . '</div>' .
                '<h5 class="text-dark text-lg font-semibold mb-3.5 dark:text-white-light">' . config($name . ".name") . '</h5>' .
                '<p class="text-white-dark text-[15px]  mb-3.5">' . config($name . ".description") . '</p>' .
                '<div class="hidden lg:flex mt-1 gap-4">' . $installable . ' <a href="javascript:;" class="btn btn-outline-danger w-1/2">ลบ</a></div>' .
                '</div>' .
                '</div>';
        }
        return view('settings.apps', ['html' => $html]);
    }

    public function download($id)
    {

    }

    public function install($id)
    {
        exec('php artisan module:enable ' . $id);
    }

    public function unInstall($id)
    {
        exec('php artisan module:delete ' . $id);
    }
}
