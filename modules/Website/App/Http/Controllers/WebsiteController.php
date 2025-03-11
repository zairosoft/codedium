<?php

namespace Modules\Website\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Website\App\Models\Page;
use Illuminate\Http\Request;
use Modules\Website\App\Providers\GenerateFrontEndService;

class WebsiteController extends Controller
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
        $pages = Page::select('pages.*')
            ->leftJoin('page_langs', 'pages.id', '=', 'page_langs.page_id')
            ->get();

        return view('website::index', ['pages' => $pages]);
    }

    public function show($slug)
    {
        $pages = Page::leftJoin('page_langs', 'pages.id', '=', 'page_langs.page_id')
            ->select('pages.id, pages.is_published', 'page_langs.*')
            ->where('page_langs.slug', $slug)
            ->where('page_langs.code', app()->getLocale())
            ->get();

        return view('website::pages.show', compact('page'));
    }

    public function create()
    {
        return view('website::create');
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
        return view('website::edit', compact('page'));
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
    }
}
