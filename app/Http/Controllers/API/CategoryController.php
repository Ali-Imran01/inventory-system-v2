<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\AuditService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($validated);
        AuditService::log('CATEGORY_CREATED', $category->toArray());

        return response()->json($category, 201);
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);
        AuditService::log('CATEGORY_UPDATED', $category->toArray());

        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $payload = $category->toArray();
        $category->delete();
        AuditService::log('CATEGORY_DELETED', $payload);

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
