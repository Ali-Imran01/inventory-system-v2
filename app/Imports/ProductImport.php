<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // 1. Find or create Category
        $category = Category::firstOrCreate(['name' => $row['category']]);

        // 2. Find or create Unit
        $unit = Unit::firstOrCreate(['name' => $row['unit'] ?? 'pcs']);

        // 3. Create Product
        $product = Product::create([
            'sku'         => $row['sku'],
            'name'        => $row['name'],
            'category_id' => $category->id,
            'unit_id'     => $unit->id,
            'cost_price'  => $row['cost_price'] ?? 0,
            'sell_price'  => $row['sell_price'] ?? 0,
            'min_stock'   => $row['min_stock'] ?? 0,
        ]);

        // 4. Handle initial stock if provided
        if (isset($row['initial_stock']) && $row['initial_stock'] > 0) {
            StockMovement::create([
                'product_id'   => $product->id,
                'warehouse_id' => $row['warehouse_id'] ?? 1, // Default to first warehouse
                'type'         => 'IN',
                'quantity'     => $row['initial_stock'],
                'user_id'      => Auth::id() ?? 1,
                'reference'    => 'EXCEL-IMPORT-INITIAL',
                'notes'        => 'Initial stock from Excel import',
            ]);
        }

        return $product;
    }

    public function rules(): array
    {
        return [
            'sku' => 'required|unique:products,sku',
            'name' => 'required',
            'category' => 'required',
            'cost_price' => 'numeric|min:0',
            'sell_price' => 'numeric|min:0',
            'initial_stock' => 'nullable|numeric|min:0',
        ];
    }
}
