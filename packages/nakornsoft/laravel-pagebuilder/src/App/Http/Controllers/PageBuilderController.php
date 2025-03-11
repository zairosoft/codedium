<?php

namespace Nakornsoft\PageBuilder\App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Nakornsoft\PageBuilder\App\Models\Page;

use Nakornsoft\PageBuilder\App\Providers\GenerateFrontEndService;



class PageBuilderController extends Controller
{

    private $generateFrontend;

    public function __construct(GenerateFrontEndService $generateFrontend)
    {
        $this->generateFrontend = $generateFrontend;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::get();
        echo "<pre>";
        print_r($pages);
        echo "</pre>";

        ///return view('pagebuilder::builder');
    }

    public function show($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('published', true)
            ->firstOrFail();

        return view('pages.show', compact('page'));
    }

    public function create()
    {
        return view('pagebuilder::create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'is_published' => 'boolean',
        ]);

        Page::create($validated);
    }

    public function edit(Page $page)
    {
        return view('pagebuilder::edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'nullable|string',
            'published' => 'boolean',
        ]);
        $page = Page::findOrfail($page->id);
        $page->update($validated);
        $this->generateFrontend->generatePage($page);
        echo $page;
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully');
    }
}
