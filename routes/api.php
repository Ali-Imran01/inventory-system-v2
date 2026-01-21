<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\UnitController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\StockController;
use App\Http\Controllers\API\SupplierController;
use App\Http\Controllers\API\WarehouseController;
use App\Http\Controllers\API\AuditLogController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\DocumentController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ImportController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Core Modules
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('units', UnitController::class);
    
    Route::get('/products/low-stock', [ProductController::class, 'lowStock']);
    Route::apiResource('products', ProductController::class);

    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('warehouses', WarehouseController::class);

    // Stock Management
    Route::post('/stock/in', [StockController::class, 'stockIn']);
    Route::post('/stock/out', [StockController::class, 'stockOut']);
    Route::get('/stock/history', [StockController::class, 'history']);

    // Admin Only (Audit Logs & Reports)
    Route::get('/audit-logs', [AuditLogController::class, 'index']);
    
    Route::prefix('reports')->group(function () {
        Route::get('/movement-history', [ReportController::class, 'movement']);
        Route::get('/valuation', [ReportController::class, 'valuation']);
        Route::get('/export-csv', [ReportController::class, 'exportCsv']);
        Route::get('/charts/stock-trend', [ReportController::class, 'stockTrendData']);
        Route::get('/charts/category-distribution', [ReportController::class, 'categoryDistribution']);
        Route::get('/charts/warehouse-comparison', [ReportController::class, 'warehouseComparison']);
    });

    // PDF Documents
    Route::get('/documents/receipt/{movement}', [DocumentController::class, 'stockMovementReceipt']);
    Route::get('/documents/valuation', [DocumentController::class, 'inventoryValuationReport']);

    // Team Management
    Route::apiResource('/users', UserController::class);

    // Data Import
    Route::post('/import/products', [ImportController::class, 'importProducts']);
});
