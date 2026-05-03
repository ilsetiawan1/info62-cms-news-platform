<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.advertisements.index', compact('advertisements'));
    }

    public function create()
    {
        return view('admin.advertisements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'image_path' => 'required|image|max:2048',
            'url'        => 'nullable|url',
            'position'   => 'required|in:header,sidebar_top,sidebar_mid,article_mid,article_bottom',
            'status'     => 'required|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('ads', 'public');
        }

        Advertisement::create($validated);

        return redirect()->route('advertisements.index')->with('success', 'Iklan berhasil ditambahkan.');
    }

    public function edit(Advertisement $advertisement)
    {
        return view('admin.advertisements.edit', compact('advertisement'));
    }

    public function update(Request $request, Advertisement $advertisement)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'image_path' => 'nullable|image|max:2048',
            'url'        => 'nullable|url',
            'position'   => 'required|in:header,sidebar_top,sidebar_mid,article_mid,article_bottom',
            'status'     => 'required|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($request->hasFile('image_path')) {
            if ($advertisement->image_path && Storage::disk('public')->exists($advertisement->image_path)) {
                Storage::disk('public')->delete($advertisement->image_path);
            }
            $validated['image_path'] = $request->file('image_path')->store('ads', 'public');
        }

        $advertisement->update($validated);

        return redirect()->route('advertisements.index')->with('success', 'Iklan berhasil diperbarui.');
    }

    public function destroy(Advertisement $advertisement)
    {
        if ($advertisement->image_path && Storage::disk('public')->exists($advertisement->image_path)) {
            Storage::disk('public')->delete($advertisement->image_path);
        }
        $advertisement->delete();

        return redirect()->route('advertisements.index')->with('success', 'Iklan berhasil dihapus.');
    }
}
