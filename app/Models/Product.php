<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'category_id',
        'unit_id',
        'cost_price',
        'sell_price',
        'min_stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getTotalStockAttribute()
    {
        $in = $this->stockMovements()->where('type', 'IN')->sum('quantity');
        $out = $this->stockMovements()->where('type', 'OUT')->sum('quantity');

        return $in - $out;
    }
}
