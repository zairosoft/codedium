<?php

namespace Nakornsoft\PageBuilder\App\Providers;

class GenerateFrontEndService
{

    public function __construct() {}

    public function generatePage($page)
    {
        $slug = $page->slug === '/' ? 'home-page' : $page->slug;
        $page_data = $page->content;
        $html      = $this->htmlInitialization($page_data, $slug);

        $this->createBladeFile($slug, $html);
        $this->generateRoute($slug, $page->slug);
    }

    public function updateRouteName($old_slug, $new_slug)
    {
        $routes = __DIR__ . './../routes/frontend.php';

        $old_method = $this->getControllerMethodName($old_slug);

        $new_method = $this->getControllerMethodName($new_slug);

        if ($old_slug === '/') {
            file_put_contents($routes, str_replace(
                "Route::get('/', [FrontendController::class, '" . $old_method . "']);\n",
                "Route::get('" . $new_slug . "', [FrontendController::class, '" . $new_method . "']);\n",
                file_get_contents($routes)
            ));
        } else {
            file_put_contents($routes, str_replace(
                "Route::get('" . $old_slug . "', [FrontendController::class, '" . $old_method . "']);\n",
                "Route::get('" . $new_slug . "', [FrontendController::class, '" . $new_method . "']);\n",
                file_get_contents($routes)
            ));
        }

        $this->updateControllerMethod($old_slug, $new_slug, $new_method, $old_method);
    }

    protected function getControllerMethodName($slug)
    {
        $method = $slug === '/' ? 'home-page' : $slug;

        $slug_remove_dash = explode('-', $method);

        foreach ($slug_remove_dash as $key => $value) {
            $slug_remove_dash[$key] = ucwords($value);
        }

        return $method_name = implode($slug_remove_dash);
    }

    protected function updateControllerMethod($old_view, $new_view, $new_method, $old_method)
    {
        $controller = __DIR__ . './../Http/Controllers/FrontendController.php';

        $new_target_view = $new_view === '/' ? 'home-page' : $new_view;

        $old_target_view = $old_view === '/' ? 'home-page' : $old_view;

        $old_method_content = "\n    public function " . $old_method . "() \n    {\n       return view('pagebuilder::pages/" . $old_target_view . "');\n    }\n";

        $new_method_content = "\n    public function " . $new_method . "() \n    {\n       return view('pagebuilder::pages/" . $new_target_view . "');\n    }\n";

        $content = file_get_contents($controller);

        file_put_contents($controller, str_replace(
            $old_method_content,
            $new_method_content,
            file_get_contents($controller)
        ));

        $old_view_name = __DIR__ . './../resources/views/pages/' . $old_target_view . '.blade.php';
        $new_view_name = __DIR__ . './../resources/views/pages/' . $new_target_view . '.blade.php';

        if (file_exists($old_view_name)) {
            $this->renameFile($old_view_name, $new_view_name);
        }
    }


    private function createBladeFile($slug, $html)
    {
        $file_name = $slug . '.blade.php';

        $dir_path = __DIR__ . './../resources/views/pages/';

        if (!is_dir($dir_path)) {
            mkdir($dir_path);
        }

        $file = fopen($dir_path . $file_name, 'w');

        fwrite($file, "@extends('pagebuilder::layouts.master')\n\n@push('page-style')\n@endpush\n@section('content')\n " . $html . "\n\n@endsection");

        fclose($file);
    }


    protected function generateRoute($slug, $route)
    {
        $routes = __DIR__ . './../routes/frontend.php';

        $method_name = $this->getControllerMethodName($slug);

        if ($route === '/') {
            file_put_contents($routes, file_get_contents($routes) . "Route::get('/', [FrontendController::class, '" . $method_name . "']);\n");
        }

        if (!str_contains(file_get_contents($routes), $route)) {
            file_put_contents($routes, file_get_contents($routes) . "Route::get('" . $route . "', [FrontendController::class, '" . $method_name . "']);\n");
        }

        $this->addControllerMethod($method_name, $slug);
    }

    protected function addControllerMethod($method_name, $slug)
    {
        $controller = __DIR__ . './../Http/Controllers/FrontendController.php';

        $target_view = $slug === '/' ? 'home-page' : $slug;

        $method = "\n    public function " . $method_name . "() \n    {\n       return view('pagebuilder::pages/" . $target_view . "');\n    }\n";

        $content = file_get_contents($controller);

        if (!str_contains($content, 'public function ' . $method_name . '()')) {

            $content_array = explode("\n", $content);

            $final_content_array = array_slice($content_array, 0, count($content_array) - 2, true);

            $new_method_array = explode("\n", $method);

            $final_contents = array_merge($final_content_array, $new_method_array);

            file_put_contents($controller, implode("\n", $final_contents) . "\n}");
        }
    }

    public function destroyPage($page)
    {
        $slug = $page->slug === '/' ? 'home-page' : $page->slug;
        $this->removeView($slug);
        $this->removeRoute($slug);
    }

    protected function removeView($slug)
    {
        $file_name = $slug === '/' ? 'home-page.blade.php' : $slug . '.blade.php';
        $view = __DIR__ . './../resources/views/pages' . $file_name;
        if (file_exists($view)) {
            unlink($view);
        }
    }

    protected function renameFile($file, $newName)
    {
        rename($file, $newName);
    }



    protected function removeRoute($route)
    {
        $routes = __DIR__ . './../routes/frontend.php';

        $method_name = $this->getControllerMethodName($route);

        if ($route === '/') {
            file_put_contents($routes, str_replace(
                "Route::get('/', [FrontendController::class, '" . $method_name . "']);\n",
                '',
                file_get_contents($routes)
            ));
        } else {
            file_put_contents($routes, str_replace(
                "Route::get('" . $route . "', [FrontendController::class, '" . $method_name . "']);\n",
                '',
                file_get_contents($routes)
            ));
        }

        $this->removeControllerMethod($method_name, $route);
    }

    protected function removeControllerMethod($method_name, $view)
    {
        $controller = __DIR__ . './../Http/Controllers/FrontendController.php';

        $method = "\n    public function " . $method_name . "() \n    {\n       return view('pagebuilder::pages/" . $view . "');\n    }\n";

        file_put_contents($controller, str_replace($method, '', file_get_contents($controller)));
    }

    protected function htmlInitialization($html, $slug)
    {
        $html = str_replace('<html>', '<html lang="en">', $html);
        $html = str_replace('<head>', '<head> <meta charset="UTF-8">', $html);
        $html = str_replace('<title></title>', '<title>' . $slug . '</title>', $html);
        $html = str_replace('<body>', '<body class="bg-light">', $html);

        return $html;
    }
}
