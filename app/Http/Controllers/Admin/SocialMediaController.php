<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SocialMediaController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $search = $request->input('search');

        if ($status === 'trash') {
            $query = SocialMedia::onlyTrashed();
        } else {
            $query = SocialMedia::query();
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('platform', 'like', '%' . $search . '%')
                  ->orWhere('url', 'like', '%' . $search . '%');
            });
        }

        $socials = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'all'      => SocialMedia::count(),
            'active'   => SocialMedia::where('is_active', true)->count(),
            'inactive' => SocialMedia::where('is_active', false)->count(),
            'trash'    => SocialMedia::onlyTrashed()->count(),
        ];

        return view('admin.socials.index', compact('socials', 'status', 'counts'));
    }

    public function create()
    {
        return view('admin.socials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform'  => 'required|string|max:50',
            'url'       => 'required|url|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        SocialMedia::create($validated);

        Cache::forget('active_social_media');

        return redirect()->route('socials.index')->with('success', 'Sosial Media berhasil ditambahkan.');
    }

    public function edit(SocialMedia $social)
    {
        return view('admin.socials.edit', compact('social'));
    }

    public function update(Request $request, SocialMedia $social)
    {
        $validated = $request->validate([
            'platform'  => 'required|string|max:50',
            'url'       => 'required|url|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $social->update($validated);

        Cache::forget('active_social_media');

        return redirect()->route('socials.index')->with('success', 'Sosial Media berhasil diperbarui.');
    }

    public function destroy(SocialMedia $social)
    {
        $social->delete();

        Cache::forget('active_social_media');

        return redirect()->route('socials.index')->with('success', 'Sosial Media berhasil dipindahkan ke Sampah.');
    }

    public function restore($id)
    {
        $social = SocialMedia::onlyTrashed()->findOrFail($id);
        $social->restore();

        Cache::forget('active_social_media');

        return redirect()->route('socials.index', ['status' => 'trash'])->with('success', 'Sosial Media berhasil dikembalikan dari Sampah.');
    }

    public function forceDelete($id)
    {
        $social = SocialMedia::onlyTrashed()->findOrFail($id);
        $social->forceDelete();

        Cache::forget('active_social_media');

        return redirect()->route('socials.index', ['status' => 'trash'])->with('success', 'Sosial Media berhasil dihapus secara permanen.');
    }

    public function bulkAction(Request $request)
    {
        $ids = $request->input('ids', []);
        $action = $request->input('action', 'delete');

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada sosial media yang dipilih.');
        }

        if ($action === 'force-delete') {
            SocialMedia::onlyTrashed()->whereIn('id', $ids)->forceDelete();
        } elseif ($action === 'restore') {
            SocialMedia::onlyTrashed()->whereIn('id', $ids)->restore();
        } else {
            SocialMedia::whereIn('id', $ids)->delete();
        }

        Cache::forget('active_social_media');

        $message = $action === 'force-delete' ? 'dihapus secara permanen.' : ($action === 'restore' ? 'dikembalikan dari Sampah.' : 'dipindahkan ke Sampah.');
        return redirect()->back()->with('success', count($ids) . ' sosial media berhasil ' . $message);
    }
}
