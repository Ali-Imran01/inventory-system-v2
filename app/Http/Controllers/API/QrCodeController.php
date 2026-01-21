<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    /**
     * Generate a QR code for a specific product.
     */
    public function generateProductQr(Product $product)
    {
        // We use the product SKU as the content of the QR code
        // This makes it compatible with our existing BarcodeScanner component
        $qrCode = QrCode::size(300)
            ->format('svg')
            ->margin(1)
            ->generate($product->sku);

        return response($qrCode)->header('Content-Type', 'image/svg+xml');
    }
}
