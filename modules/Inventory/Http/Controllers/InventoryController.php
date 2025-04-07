<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductLang;
use Modules\Inventory\Models\ProductImg;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\CategoryLang;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'lock']);
        $this->middleware('permission:inventory view', ['only' => ['index','overview','report','show']]);
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
        $products = Product::join('product_langs', 'product_langs.product_id', '=', 'products.id')
            ->where('product_langs.code', '=', app()->getLocale())
            ->where('products.company_id', '=', Auth::user()->company_id)
            ->get([
                'products.*',
                'product_langs.name',
                'product_langs.manufacturer_name',
                'product_langs.manufacturer_brand',
                'product_langs.brand',
            ]);
        return view('inventory::index',["products" => $products]);
    }

    /**
     * Display an overview of inventory statistics.
     * @return Renderable
     */
    public function overview()
    {
        $totalProducts = Product::where('company_id', Auth::user()->company_id)->count();
        $lowStockProducts = Product::where('company_id', Auth::user()->company_id)
            ->where('stock', '<', 10)
            ->count();
        $categoriesCount = Category::where('company_id', Auth::user()->company_id)->count();

        return view('inventory::overview', [
            'totalProducts' => $totalProducts,
            'lowStockProducts' => $lowStockProducts,
            'categoriesCount' => $categoriesCount
        ]);
    }

    /**
     * Display inventory reports.
     * @return Renderable
     */
    public function report()
    {
        $products = Product::join('product_langs', 'product_langs.product_id', '=', 'products.id')
            ->where('product_langs.code', '=', app()->getLocale())
            ->where('products.company_id', '=', Auth::user()->company_id)
            ->orderBy('products.stock', 'asc')
            ->get([
                'products.*',
                'product_langs.name',
                'product_langs.manufacturer_name',
                'product_langs.brand',
            ]);

        return view('inventory::report', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $categories = Category::join('product_category_langs as catelang', 'catelang.category_id', '=', 'product_categories.id')
        ->where('catelang.code', '=', app()->getLocale())
        ->where('product_categories.company_id', '=', Auth::user()->company_id)
        ->get([
                'product_categories.id',
                'product_categories.img',
                'catelang.name'
            ]);

        return view('inventory::create',["categories" => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required|exists:product_categories,id',
            'price' => 'required|numeric',
            'model' => 'required',
            'stock' => 'required|integer',
            'status' => 'required|in:0,1,2',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Save product
            $product = new Product();
            $product->company_id = Auth::user()->company_id;
            $product->category_id = $request->category_id;
            $product->discount_id = $request->discount_id ?? 0;
            $product->barcode = $request->barcode;
            $product->price = $request->price;
            $product->cost = $request->cost;
            $product->model = $request->model;
            $product->img = $request->has('product_image') ? $this->uploadImage($request->product_image) : '';
            $product->sku = $request->sku;
            $product->stock = $request->stock;
            $product->status = $request->status;
            $product->publish_schedule = $request->publish_schedule;
            $product->created_by = Auth::id();
            $product->updated_by = Auth::id();
            $product->save();

            // Save product translation
            $productLang = new ProductLang();
            $productLang->product_id = $product->id;
            $productLang->code = app()->getLocale();
            $productLang->name = $request->name;
            $productLang->manufacturer_name = $request->manufacturer_name;
            $productLang->manufacturer_brand = $request->manufacturer_brand;
            $productLang->brand = $request->brand;
            $productLang->description = $request->description;
            $productLang->short_description = $request->short_description;
            $productLang->meta_title = $request->meta_title;
            $productLang->meta_keywords = $request->meta_keywords;
            $productLang->meta_description = $request->meta_description;
            $productLang->note = $request->note;
            $productLang->save();

            // Save additional images if provided
            if ($request->has('additional_images')) {
                foreach ($request->additional_images as $index => $image) {
                    $productImg = new ProductImg();
                    $productImg->product_id = $product->id;
                    $productImg->img = $this->uploadImage($image);
                    $productImg->sort_order = $index;
                    $productImg->save();
                }
            }

            DB::commit();

            return response()->json(['success' => true, 'product_id' => $product->id]);
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
        $product = Product::where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->with(['category', 'translations' => function($query) {
                $query->where('code', app()->getLocale());
            }, 'images'])
            ->firstOrFail();

        return view('inventory::show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $product = Product::where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->with(['translations' => function($query) {
                $query->where('code', app()->getLocale());
            }, 'images'])
            ->firstOrFail();

        $categories = Category::join('product_category_langs as catelang', 'catelang.category_id', '=', 'product_categories.id')
            ->where('catelang.code', '=', app()->getLocale())
            ->where('product_categories.company_id', '=', Auth::user()->company_id)
            ->get([
                'product_categories.id',
                'product_categories.img',
                'catelang.name'
            ]);

        return view('inventory::edit', [
            'product' => $product,
            'categories' => $categories
        ]);
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
            'name' => 'required',
            'category_id' => 'required|exists:product_categories,id',
            'price' => 'required|numeric',
            'model' => 'required',
            'stock' => 'required|integer',
            'status' => 'required|in:0,1,2',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Update product
            $product = Product::where('company_id', Auth::user()->company_id)
                ->where('id', $id)
                ->firstOrFail();

            $product->category_id = $request->category_id;
            $product->discount_id = $request->discount_id ?? 0;
            $product->barcode = $request->barcode;
            $product->price = $request->price;
            $product->cost = $request->cost;
            $product->model = $request->model;
            if ($request->has('product_image')) {
                $product->img = $this->uploadImage($request->product_image);
            }
            $product->sku = $request->sku;
            $product->stock = $request->stock;
            $product->status = $request->status;
            $product->publish_schedule = $request->publish_schedule;
            $product->updated_by = Auth::id();
            $product->save();

            // Update product translation
            $productLang = ProductLang::where('product_id', $product->id)
                ->where('code', app()->getLocale())
                ->first();

            if (!$productLang) {
                $productLang = new ProductLang();
                $productLang->product_id = $product->id;
                $productLang->code = app()->getLocale();
            }

            $productLang->name = $request->name;
            $productLang->manufacturer_name = $request->manufacturer_name;
            $productLang->manufacturer_brand = $request->manufacturer_brand;
            $productLang->brand = $request->brand;
            $productLang->description = $request->description;
            $productLang->short_description = $request->short_description;
            $productLang->meta_title = $request->meta_title;
            $productLang->meta_keywords = $request->meta_keywords;
            $productLang->meta_description = $request->meta_description;
            $productLang->note = $request->note;
            $productLang->save();

            // Handle additional images if requested
            if ($request->has('delete_images')) {
                ProductImg::whereIn('id', $request->delete_images)->delete();
            }

            if ($request->has('additional_images')) {
                foreach ($request->additional_images as $index => $image) {
                    $productImg = new ProductImg();
                    $productImg->product_id = $product->id;
                    $productImg->img = $this->uploadImage($image);
                    $productImg->sort_order = $index;
                    $productImg->save();
                }
            }

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
            'id' => 'required|exists:products,id'
        ]);

        try {
            $product = Product::where('company_id', Auth::user()->company_id)
                ->where('id', $request->id)
                ->firstOrFail();

            $product->delete();

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
        $image->storeAs('public/products', $filename);
        return 'products/' . $filename;
    }
}
