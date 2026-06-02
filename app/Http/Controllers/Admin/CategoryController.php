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
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort', 'date');

        $query = Category::whereNull('parent_id')
            ->with(['children' => function($q) use ($sortBy) {
                if ($sortBy === 'name') {
                    $q->orderBy('name', 'asc');
                } else {
                    $q->orderBy('created_at', 'desc');
                }
            }])
            ->withCount('children');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('children', function ($cq) use ($search) {
                      $cq->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($sortBy === 'name') {
            $query->orderBy('name', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $categories = $query->paginate(10)->withQueryString();
            
        return view('admin.categories.index', compact('categories', 'sortBy'));
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
     * Reverse the specified category.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    /**
     * Delete multiple categories.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada kategori yang dipilih.');
        }

        // Deleting categories. Foreign key constraints:
        // articles.category_id ON DELETE CASCADE (will delete articles in deleted categories)
        // categories.parent_id ON DELETE SET NULL (will clear parent_id for subcategories)
        Category::whereIn('id', $ids)->delete();

        return redirect()->route('categories.index')->with('success', count($ids) . ' kategori berhasil dihapus.');
    }
}
