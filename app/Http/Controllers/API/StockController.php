<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function stockIn(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
            'reference' => 'nullable|string',
        ]);

        $movement = StockMovement::create([
            'product_id' => $validated['product_id'],
            'warehouse_id' => $validated['warehouse_id'],
            'type' => 'IN',
            'quantity' => $validated['quantity'],
            'reference' => $validated['reference'],
            'user_id' => Auth::id(),
        ]);

        AuditService::log('STOCK_IN', $movement->toArray());

        return response()->json([
            'message' => 'Stock added successfully',
            'movement' => $movement
        ], 201);
    }

    public function stockOut(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
            'reference' => 'nullable|string',
        ]);

        // Note: For multi-warehouse, we should ideally check stock IN that specific warehouse.
        // For now, keeping it simple but adding the field.
        $product = Product::findOrFail($validated['product_id']);

        if ($product->total_stock < $validated['quantity']) {
            return response()->json([
                'message' => 'Insufficient stock'
            ], 422);
        }

        $movement = StockMovement::create([
            'product_id' => $validated['product_id'],
            'warehouse_id' => $validated['warehouse_id'],
            'type' => 'OUT',
            'quantity' => $validated['quantity'],
            'reference' => $validated['reference'],
            'user_id' => Auth::id(),
        ]);

        AuditService::log('STOCK_OUT', $movement->toArray());

        return response()->json([
            'message' => 'Stock removed successfully',
            'movement' => $movement
        ], 201);
    }

    public function history(Request $request)
    {
        $history = StockMovement::with(['product', 'user', 'warehouse'])
            ->latest()
            ->paginate(20);

        return response()->json($history);
    }
}
