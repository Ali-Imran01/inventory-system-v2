<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function stockMovementReceipt(StockMovement $movement)
    {
        $movement->load(['product.unit', 'warehouse', 'user']);
        
        $pdf = Pdf::loadView('pdfs.stock-movement', compact('movement'));
        
        return $pdf->download("receipt-{$movement->id}.pdf");
    }

    public function inventoryValuationReport()
    {
        $products = Product::with(['category', 'unit'])->get();
        $totalValue = $products->sum(function($p) {
            return $p->total_stock * $p->cost_price;
        });

        $pdf = Pdf::loadView('pdfs.inventory-valuation', compact('products', 'totalValue'));
        
        return $pdf->download("inventory-valuation-" . now()->format('Y-m-d') . ".pdf");
    }
}
