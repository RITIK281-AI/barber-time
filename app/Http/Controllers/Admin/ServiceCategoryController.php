<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceCategoryController extends Controller
{
    // show all categories
    public function index()
    {
        $categories = ServiceCategory::withCount('services')
            ->orderBy('sort_order')
            ->get();

        return view('admin.category.index', compact('categories'));
    }

    // save a new category
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255|unique:service_categories,name',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        ServiceCategory::create([
            'name'       => $request->name,
            'slug'       => Str::slug($request->name),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return back()->with('success', 'Category added successfully.');
    }

    // update an existing category
    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $request->validate([
            'name'       => 'required|string|max:255|unique:service_categories,name,' . $serviceCategory->id,
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $serviceCategory->update([
            'name'       => $request->name,
            'slug'       => Str::slug($request->name),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return back()->with('success', 'Category updated successfully.');
    }

    // delete a category only if no services are linked to it
    public function destroy(ServiceCategory $serviceCategory)
    {
        if ($serviceCategory->services()->count() > 0) {
            return back()->with('error', 'Cannot delete category. It has services linked to it.');
        }

        $serviceCategory->delete();

        return back()->with('success', 'Category deleted successfully.');
    }
}
