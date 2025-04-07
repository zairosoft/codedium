<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\CategoryLang;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'lock']);
        $this->middleware('permission:inventory view', ['only' => ['index','show']]);
        $this->middleware('permission:inventory create', ['only' => ['create','store']]);
        $this->middleware('permission:inventory update', ['only' => ['edit','update']]);
        $this->middleware('permission:inventory delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $categories = Category::join('product_category_langs', 'product_category_langs.category_id', '=', 'product_categories.id')
            ->where('product_category_langs.code', '=', app()->getLocale())
            ->where('product_categories.company_id', '=', Auth::user()->company_id)
            ->get([
                'product_categories.*',
                'product_category_langs.name',
                'product_category_langs.slug',
                'product_category_langs.description',
            ]);

        return view('inventory::categories.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('inventory::categories.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Save category
            $category = new Category();
            $category->company_id = Auth::user()->company_id;
            $category->img = $request->has('category_image') ? $this->uploadImage($request->category_image) : '';
            $category->created_by = Auth::id();
            $category->updated_by = Auth::id();
            $category->save();

            // Save category translation
            $categoryLang = new CategoryLang();
            $categoryLang->category_id = $category->id;
            $categoryLang->code = app()->getLocale();
            $categoryLang->name = $request->name;
            $categoryLang->slug = $request->slug ?? Str::slug($request->name);
            $categoryLang->description = $request->description;
            $categoryLang->created_by = Auth::id();
            $categoryLang->updated_by = Auth::id();
            $categoryLang->save();

            DB::commit();

            return response()->json(['success' => true, 'category_id' => $category->id]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $category = Category::where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->with(['translations' => function($query) {
                $query->where('code', app()->getLocale());
            }])
            ->firstOrFail();

        return view('inventory::categories.show', ['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $category = Category::where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->with(['translations' => function($query) {
                $query->where('code', app()->getLocale());
            }])
            ->firstOrFail();

        return view('inventory::categories.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Update category
            $category = Category::where('company_id', Auth::user()->company_id)
                ->where('id', $id)
                ->firstOrFail();

            if ($request->has('category_image')) {
                $category->img = $this->uploadImage($request->category_image);
            }
            $category->updated_by = Auth::id();
            $category->save();

            // Update category translation
            $categoryLang = CategoryLang::where('category_id', $category->id)
                ->where('code', app()->getLocale())
                ->first();

            if (!$categoryLang) {
                $categoryLang = new CategoryLang();
                $categoryLang->category_id = $category->id;
                $categoryLang->code = app()->getLocale();
                $categoryLang->created_by = Auth::id();
            }

            $categoryLang->name = $request->name;
            $categoryLang->slug = $request->slug ?? Str::slug($request->name);
            $categoryLang->description = $request->description;
            $categoryLang->updated_by = Auth::id();
            $categoryLang->save();

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:product_categories,id'
        ]);

        try {
            // Check if category has products
            $productsCount = DB::table('products')
                ->where('category_id', $request->id)
                ->count();

            if ($productsCount > 0) {
                return response()->json([
                    'error' => 'Cannot delete category. It has ' . $productsCount . ' products associated with it.'
                ], 422);
            }

            $category = Category::where('company_id', Auth::user()->company_id)
                ->where('id', $request->id)
                ->firstOrFail();

            $category->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Helper method to upload images
     * @param $image
     * @return string
     */
    private function uploadImage($image)
    {
        // Replace with your actual image upload logic
        $filename = time() . '_' . $image->getClientOriginalName();
        $image->storeAs('public/categories', $filename);
        return 'categories/' . $filename;
    }
}
