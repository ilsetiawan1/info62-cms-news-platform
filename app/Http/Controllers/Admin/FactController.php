<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fact;
use Illuminate\Http\Request;

class FactController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $search = $request->input('search');

        if ($status === 'trash') {
            $query = Fact::onlyTrashed();
        } else {
            $query = Fact::query();
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($search) {
            $query->where('content', 'like', '%' . $search . '%');
        }

        $facts = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'all'      => Fact::count(),
            'active'   => Fact::where('is_active', true)->count(),
            'inactive' => Fact::where('is_active', false)->count(),
            'trash'    => Fact::onlyTrashed()->count(),
        ];

        return view('admin.facts.index', compact('facts', 'status', 'counts'));
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
        return redirect()->route('facts.index')->with('success', 'Fakta berhasil dipindahkan ke Sampah.');
    }

    public function restore($id)
    {
        $fact = Fact::onlyTrashed()->findOrFail($id);
        $fact->restore();

        return redirect()->route('facts.index', ['status' => 'trash'])->with('success', 'Fakta berhasil dikembalikan dari Sampah.');
    }

    public function forceDelete($id)
    {
        $fact = Fact::onlyTrashed()->findOrFail($id);
        $fact->forceDelete();

        return redirect()->route('facts.index', ['status' => 'trash'])->with('success', 'Fakta berhasil dihapus secara permanen.');
    }

    public function bulkAction(Request $request)
    {
        $ids = $request->input('ids', []);
        $action = $request->input('action', 'delete');

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada fakta yang dipilih.');
        }

        if ($action === 'force-delete') {
            Fact::onlyTrashed()->whereIn('id', $ids)->forceDelete();
            return redirect()->back()->with('success', count($ids) . ' fakta berhasil dihapus secara permanen.');
        } elseif ($action === 'restore') {
            Fact::onlyTrashed()->whereIn('id', $ids)->restore();
            return redirect()->back()->with('success', count($ids) . ' fakta berhasil dikembalikan dari Sampah.');
        } else {
            Fact::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', count($ids) . ' fakta berhasil dipindahkan ke Sampah.');
        }
    }
}
