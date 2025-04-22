<?php

namespace Modules\PageBuilder\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\PageBuilder\App\Models\Page;
use Illuminate\Support\Str;

class PageBuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::all();
        return view('pagebuilder::pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pagebuilder::pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'html_content' => 'nullable',
            'css_content' => 'nullable',
            'js_content' => 'nullable',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $slug = Str::slug($request->title);
        
        // Check if slug exists and append number if needed
        $count = 1;
        $originalSlug = $slug;
        
        while (Page::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        Page::create([
            'title' => $request->title,
            'slug' => $slug,
            'html_content' => $request->html_content,
            'css_content' => $request->css_content,
            'js_content' => $request->js_content,
            'is_published' => $request->has('is_published'),
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        return redirect()->route('pagebuilder.index')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $page = Page::findOrFail($id);
        return view('pagebuilder::pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('pagebuilder::pages.edit', compact('page'));
    }

    /**
     * Show the vvvebJs editor for the specified page.
     */
    public function builder($id)
    {
        $page = Page::findOrFail($id);
        return view('pagebuilder::builder.editor', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $page = Page::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'html_content' => 'nullable',
            'css_content' => 'nullable',
            'js_content' => 'nullable',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $page->update([
            'title' => $request->title,
            'html_content' => $request->html_content,
            'css_content' => $request->css_content,
            'js_content' => $request->js_content,
            'is_published' => $request->has('is_published'),
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        return redirect()->route('pagebuilder.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Save the page content from the vvvebJs editor.
     */
    public function saveBuilder(Request $request, $id): RedirectResponse
    {
        $page = Page::findOrFail($id);
        
        $page->update([
            'html_content' => $request->html,
            'css_content' => $request->css,
            'js_content' => $request->js,
        ]);

        return redirect()->route('pagebuilder.index')
            ->with('success', 'Page content saved successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return redirect()->route('pagebuilder.index')
            ->with('success', 'Page deleted successfully.');
    }
    
    /**
     * Preview the page content.
     */
    public function preview($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return view('pagebuilder::pages.preview', compact('page'));
    }
    
    /**
     * Serve iframe content for the page builder.
     * This route is specifically used by VvvebJs when loading the iframe.
     */
    public function iframeContent($id)
    {
        $page = Page::findOrFail($id);
        return view('pagebuilder::pages.preview', compact('page'));
    }
}
