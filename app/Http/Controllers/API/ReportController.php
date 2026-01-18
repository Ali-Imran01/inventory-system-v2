<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Get fliterable stock movement history.
     */
    public function movement(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        return response()->json($query->latest()->paginate(50));
    }

    /**
     * Get total inventory valuation.
     */
    public function valuation()
    {
        $products = Product::all();
        $totalValue = $products->sum(function ($product) {
            return $product->total_stock * $product->cost_price;
        });

        $items = $products->map(function ($p) {
            return [
                'name' => $p->name,
                'stock' => $p->total_stock,
                'cost_price' => $p->cost_price,
                'value' => round($p->total_stock * $p->cost_price, 2)
            ];
        });

        return response()->json([
            'total_value' => round($totalValue, 2),
            'currency' => 'USD',
            'product_count' => $products->count(),
            'items' => $items
        ]);
    }

    /**
     * Export products to CSV.
     */
    public function exportCsv()
    {
        $products = Product::with(['category', 'unit'])->get();

        $response = new StreamedResponse(function () use ($products) {
            $handle = fopen('php://output', 'w');

            // Header
            fputcsv($handle, ['SKU', 'Name', 'Category', 'Unit', 'Cost Price', 'Sell Price', 'Min Stock', 'Current Stock']);

            foreach ($products as $product) {
                fputcsv($handle, [
                    $product->sku,
                    $product->name,
                    $product->category->name ?? 'N/A',
                    $product->unit->name ?? 'N/A',
                    $product->cost_price,
                    $product->sell_price,
                    $product->min_stock,
                    $product->total_stock,
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="inventory_report_' . date('Y-m-d') . '.csv"');

        return $response;
    }

    /**
     * Get stock trend data for the last 7 days.
     */
    public function stockTrendData()
    {
        $days = collect(range(6, 0))->map(function ($i) {
            return now()->subDays($i)->format('Y-m-d');
        });

        $movements = StockMovement::where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->get()
            ->groupBy(function ($m) {
                return $m->created_at->format('Y-m-d');
            });

        $data = $days->map(function ($day) use ($movements) {
            $dayMovements = $movements->get($day, collect());
            return [
                'date' => $day,
                'in' => (int) $dayMovements->where('type', 'IN')->sum('quantity'),
                'out' => (int) $dayMovements->where('type', 'OUT')->sum('quantity'),
            ];
        });

        return response()->json($data);
    }

    /**
     * Get stock distribution by category.
     */
    public function categoryDistribution()
    {
        $products = Product::with('category')->get();
        
        $data = $products->groupBy('category_id')->map(function ($catProducts) {
            $category = $catProducts->first()->category;
            return [
                'name' => $category->name ?? 'Uncategorized',
                'value' => (float) $catProducts->sum(function ($p) {
                    return $p->total_stock * $p->cost_price;
                }),
                'count' => (int) $catProducts->sum('total_stock')
            ];
        })->values();

        return response()->json($data);
    }

    /**
     * Get stock levels by warehouse.
     */
    public function warehouseComparison()
    {
        $warehouses = \App\Models\Warehouse::all();
        
        $data = $warehouses->map(function ($w) {
            $in = StockMovement::where('warehouse_id', $w->id)->where('type', 'IN')->sum('quantity');
            $out = StockMovement::where('warehouse_id', $w->id)->where('type', 'OUT')->sum('quantity');
            
            return [
                'name' => $w->name,
                'stock' => (int) ($in - $out)
            ];
        });

        return response()->json($data);
    }
}
