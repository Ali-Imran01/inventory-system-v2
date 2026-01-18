<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Services\AuditService;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        return response()->json(Unit::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:units',
        ]);

        $unit = Unit::create($validated);
        AuditService::log('UNIT_CREATED', $unit->toArray());

        return response()->json($unit, 201);
    }

    public function show(Unit $unit)
    {
        return response()->json($unit);
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:units,name,' . $unit->id,
        ]);

        $unit->update($validated);
        AuditService::log('UNIT_UPDATED', $unit->toArray());

        return response()->json($unit);
    }

    public function destroy(Unit $unit)
    {
        $payload = $unit->toArray();
        $unit->delete();
        AuditService::log('UNIT_DELETED', $payload);

        return response()->json(['message' => 'Unit deleted successfully']);
    }
}
