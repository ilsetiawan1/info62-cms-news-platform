<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fact;
use Illuminate\Http\Request;

class FactController extends Controller
{
    public function index()
    {
        $facts = Fact::latest()->paginate(15);
        return view('admin.facts.index', compact('facts'));
    }

    public function create()
    {
        return view('admin.facts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content'   => 'required|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Fact::create($validated);

        return redirect()->route('facts.index')->with('success', 'Fakta berhasil ditambahkan.');
    }

    public function edit(Fact $fact)
    {
        return view('admin.facts.edit', compact('fact'));
    }

    public function update(Request $request, Fact $fact)
    {
        $validated = $request->validate([
            'content'   => 'required|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $fact->update($validated);

        return redirect()->route('facts.index')->with('success', 'Fakta berhasil diperbarui.');
    }

    public function destroy(Fact $fact)
    {
        $fact->delete();
        return redirect()->route('facts.index')->with('success', 'Fakta berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada fakta yang dipilih.');
        }

        Fact::whereIn('id', $ids)->delete();

        return redirect()->route('facts.index')->with('success', count($ids) . ' data fakta berhasil dihapus.');
    }
}
