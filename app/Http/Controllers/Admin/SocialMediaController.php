<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SocialMediaController extends Controller
{
    public function index()
    {
        $socials = SocialMedia::latest()->paginate(15);
        return view('admin.socials.index', compact('socials'));
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

        return redirect()->route('socials.index')->with('success', 'Sosial Media berhasil dihapus.');
    }
}
