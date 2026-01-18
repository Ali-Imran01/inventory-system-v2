<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        return Warehouse::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        return Warehouse::create($validated);
    }

    public function show(Warehouse $warehouse)
    {
        return $warehouse;
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $warehouse->update($validated);
        return $warehouse;
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return response()->noContent();
    }
}
