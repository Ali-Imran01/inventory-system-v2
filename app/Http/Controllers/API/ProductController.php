<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\AuditService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'unit']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('category', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->get()->map(function ($product) {
            $product->total_stock = $product->total_stock; // Trigger accessor
            return $product;
        });

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:products',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
        ]);

        $product = Product::create($validated);
        AuditService::log('PRODUCT_CREATED', $product->toArray());

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'unit']);
        $product->total_stock = $product->total_stock;

        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
        ]);

        $product->update($validated);
        AuditService::log('PRODUCT_UPDATED', $product->toArray());

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $payload = $product->toArray();
        $product->delete();
        AuditService::log('PRODUCT_DELETED', $payload);

        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function lowStock()
    {
        $products = Product::with(['category', 'unit'])->get()->filter(function ($product) {
            return $product->total_stock <= $product->min_stock;
        })->values();

        return response()->json($products);
    }
}
