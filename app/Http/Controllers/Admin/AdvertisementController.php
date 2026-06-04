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
            'position'   => 'required|in:slot1,slot2,slot3,slot4,slot5,slot6,slot7,slot8,slot9,slot10',
            'status'     => 'required|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validated['status'] === 'active') {
            $existingActive = Advertisement::where('position', $validated['position'])
                ->where('status', 'active')
                ->first();
            if ($existingActive) {
                return back()->withInput()->with('error', 'Gagal! Slot iklan di posisi ini masih aktif. Silakan nonaktifkan iklan lama terlebih dahulu.');
            }
        }

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
            'position'   => 'required|in:slot1,slot2,slot3,slot4,slot5,slot6,slot7,slot8,slot9,slot10',
            'status'     => 'required|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validated['status'] === 'active') {
            $existingActive = Advertisement::where('position', $validated['position'])
                ->where('status', 'active')
                ->where('id', '!=', $advertisement->id)
                ->first();
            if ($existingActive) {
                return back()->withInput()->with('error', 'Gagal! Slot iklan di posisi ini masih aktif. Silakan nonaktifkan iklan lama terlebih dahulu.');
            }
        }

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

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada iklan yang dipilih.');
        }

        $advertisements = Advertisement::whereIn('id', $ids)->get();

        foreach ($advertisements as $advertisement) {
            if ($advertisement->image_path && Storage::disk('public')->exists($advertisement->image_path)) {
                Storage::disk('public')->delete($advertisement->image_path);
            }
            $advertisement->delete();
        }

        return redirect()->route('advertisements.index')->with('success', count($ids) . ' iklan berhasil dihapus.');
    }
}
