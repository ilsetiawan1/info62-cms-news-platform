<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        // Load categories with their parent and count of children
        $categories = Category::with('parent')
            ->withCount('children')
            ->latest()
            ->paginate(10);
            
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        // Get all categories to be used as potential parents
        $categories = Category::orderBy('name')->get();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:120', 'unique:categories,slug'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
        ]);

        // Auto-generate slug if empty
        if (empty($validated['slug'])) {
            $slug = Str::slug($validated['name']);
            // Ensure unique slug
            $originalSlug = $slug;
            $counter = 1;
            while (Category::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        // Get all categories EXCEPT the current one and its children (to prevent circular reference)
        // We do a simple exclusion of itself. For full tree exclusion, a recursive function is needed,
        // but for this scope, preventing itself as parent is the minimum requirement.
        // Actually, let's also exclude direct children to be safer.
        $excludeIds = $category->children()->pluck('id')->push($category->id)->toArray();
        
        $categories = Category::whereNotIn('id', $excludeIds)->orderBy('name')->get();
        
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:120', Rule::unique('categories')->ignore($category->id)],
            'parent_id' => ['nullable', 'exists:categories,id', function ($attribute, $value, $fail) use ($category) {
                // Prevent circular reference
                if ($value == $category->id) {
                    $fail('Kategori tidak bisa menjadi induk bagi dirinya sendiri.');
                }
                // Prevent setting a child as parent
                $childrenIds = $category->children()->pluck('id')->toArray();
                if (in_array($value, $childrenIds)) {
                    $fail('Kategori tidak bisa memilih anaknya (child) sebagai induk (parent).');
                }
            }],
            'description' => ['nullable', 'string'],
        ]);

        if (empty($validated['slug'])) {
            $slug = Str::slug($validated['name']);
            $originalSlug = $slug;
            $counter = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
