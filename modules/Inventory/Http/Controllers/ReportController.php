<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Category;

class ReportController extends Controller
{
    /**
     * Display the inventory report view.
     * @return Renderable
     */
    public function index(Request $request)
    {
        // Get products with their translations
        $products = Product::select('products.*')
            ->join('product_langs', function($join) {
                $join->on('products.id', '=', 'product_langs.product_id')
                    ->where('product_langs.code', app()->getLocale());
            })
            ->with(['category', 'brand', 'manufacturer'])
            ->get()
            ->map(function($product) {
                $product->name = $product->name()->name ?? '';
                $product->description = $product->description()->description ?? '';
                $product->brand = $product->brand?->name ?? '';
                $product->manufacturer_name = $product->manufacturer?->name ?? '';
                return $product;
            });

        return view('inventory::report', compact('products'));
    }

    /**
     * Export inventory data to CSV
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exportCSV(Request $request)
    {
        // Get filtered products
        $query = Product::select('products.*')
            ->join('product_langs', function($join) {
                $join->on('products.id', '=', 'product_langs.product_id')
                    ->where('product_langs.code', app()->getLocale());
            });

        // Apply category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->where('products.category_id', $request->category);
        }

        // Apply stock status filter
        if ($request->has('stockStatus') && !empty($request->stockStatus)) {
            switch($request->stockStatus) {
                case 'out':
                    $query->where('products.stock', 0);
                    break;
                case 'critical':
                    $query->where('products.stock', '>', 0)->where('products.stock', '<=', 5);
                    break;
                case 'low':
                    $query->where('products.stock', '>', 5)->where('products.stock', '<=', 20);
                    break;
                case 'normal':
                    $query->where('products.stock', '>', 20);
                    break;
            }
        }

        // Apply date range filter
        if ($request->has('dateFrom') && $request->has('dateTo') && !empty($request->dateFrom) && !empty($request->dateTo)) {
            $query->whereBetween('products.created_at', [$request->dateFrom, $request->dateTo]);
        }

        // Get the products with their relations
        $products = $query->with(['category', 'brand', 'manufacturer'])->get()
            ->map(function($product) {
                $product->name = $product->name()->name ?? '';
                return $product;
            });

        // Set the headers for CSV export
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="inventory_report.csv"',
        ];

        // Create the CSV content
        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['ID', 'Product Name', 'Category', 'Price', 'Stock', 'Status']);

            // Add product rows
            foreach ($products as $product) {
                $status = '';
                if ($product->status == 1) $status = 'Published';
                else if ($product->status == 0) $status = 'Hidden';
                else if ($product->status == 2) $status = 'Draft';

                $categoryName = $product->category ?
                    optional($product->category->translation(app()->getLocale()))->name :
                    'Uncategorized';

                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $categoryName,
                    $product->price,
                    $product->stock,
                    $status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
