<?php

namespace Modules\Website\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Website\App\Models\Page;
use Modules\Website\App\Models\PageLang;
use Illuminate\Http\Request;
use Modules\Website\App\Providers\GenerateFrontEndService;

class WebsiteController extends Controller
{

    private $generateFrontend;

    public function __construct(GenerateFrontEndService $generateFrontend)
    {
        $this->middleware(['auth', 'lock']);
        $this->middleware('permission:website view', ['only' => ['index']]);
        $this->middleware('permission:website create', ['only' => ['create', 'store']]);
        $this->middleware('permission:website update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:website delete', ['only' => ['destroy']]);
        $this->generateFrontend = $generateFrontend;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::select('pages.id', 'pages.is_published', 'page_langs.*')
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
        return view('website::add');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255',
            ]
        );
        $page = Page::insertGetId([
            'is_published' => $request->is_published,
        ]);
        PageLang::create([
            'page_id' => (int)$page,
            'name' => $request->name,
            'slug' => $request->slug,
            'code' => app()->getLocale(),
            'content' => $request->content,
            'keywords' => $request->keywords,
            'description' => $request->description,
        ]);
        return redirect()->route('website.edit', $request->slug)->with('message', 'State saved correctly!!!');
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
