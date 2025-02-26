<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Category;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

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
            ->get([
                    'products.*',
                    'product_langs.name',
                    'product_langs.manufacturer_name',
                    'product_langs.manufacturer_brand',
                    'product_langs.brand',
                ]);
        return view('inventory::index',["products" => $products]);
    }
    public function overview()
    {
        echo "overview";
    }
    public function report()
    {
        echo "report";
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $categories = Category::join('product_category_langs as catelang', 'catelang.category_id', '=', 'product_categories.id')
        ->where('catelang.code', '=', app()->getLocale())
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
        $request->validate([
            'name' => 'required',
            'editor' => 'required'
        ]);


        Product::create();


        echo json_encode(["success" => $request->editor]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('inventory::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('inventory::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $user = User::find($request->id);
        $user->delete();
        Cache::forget('users');
        echo json_encode(["success" => $request->id]);
        //return redirect('users')->withSuccess('ลบสำเร็จ');
    }
}
