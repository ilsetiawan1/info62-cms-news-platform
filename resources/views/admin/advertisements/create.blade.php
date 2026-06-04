@extends('layouts.admin')

@section('header', 'Tambah Iklan')

@section('content')
@if(session('error'))
<div id="error-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 dark:border-gray-700 transform transition-all scale-100">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white text-center mb-2">Peringatan Bentrok Slot!</h3>
        <p class="text-sm text-slate-500 dark:text-gray-400 text-center leading-relaxed mb-6">{{ session('error') }}</p>
        <div class="flex justify-center">
            <button onclick="document.getElementById('error-modal').remove()" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl shadow-md transition-all w-full text-center">
                Saya Mengerti
            </button>
        </div>
    </div>
</div>
@endif

<div class="grid grid-cols-12 gap-6 max-w-6xl">
    <!-- Form Card -->
    <div class="col-span-12 lg:col-span-7 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <form action="{{ route('advertisements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Judul / Nama Iklan</label>
                <input type="text" name="title" required value="{{ old('title') }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white focus:ring-primary focus:border-primary px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Gambar / Banner</label>
                <input type="file" name="image_path" required accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">URL / Link Tujuan (Opsional)</label>
                <input type="url" name="url" value="{{ old('url') }}" placeholder="https://" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white focus:ring-primary focus:border-primary px-4 py-2">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Posisi</label>
                    <select name="position" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
                        <option value="slot1" {{ old('position') == 'slot1' ? 'selected' : '' }}>Slot 1 (Grid Kiri Atas - Kurs)</option>
                        <option value="slot2" {{ old('position') == 'slot2' ? 'selected' : '' }}>Slot 2 (Grid Kiri Bawah - Emas)</option>
                        <option value="slot3" {{ old('position') == 'slot3' ? 'selected' : '' }}>Slot 3 (Grid Tengah - Bawah Slider)</option>
                        <option value="slot4" {{ old('position') == 'slot4' ? 'selected' : '' }}>Slot 4 (Grid Tengah - Sela Card)</option>
                        <option value="slot5" {{ old('position') == 'slot5' ? 'selected' : '' }}>Slot 5 (Grid Tengah - Atas Terkini)</option>
                        <option value="slot6" {{ old('position') == 'slot6' ? 'selected' : '' }}>Slot 6 (Grid Tengah - Sela Terkini)</option>
                        <option value="slot7" {{ old('position') == 'slot7' ? 'selected' : '' }}>Slot 7 (Grid Tengah - Atas Footer)</option>
                        <option value="slot8" {{ old('position') == 'slot8' ? 'selected' : '' }}>Slot 8 (Grid Kanan - Bawah Terpopuler)</option>
                        <option value="slot9" {{ old('position') == 'slot9' ? 'selected' : '' }}>Slot 9 (Grid Kanan - Bawah Topik)</option>
                        <option value="slot10" {{ old('position') == 'slot10' ? 'selected' : '' }}>Slot 10 (Grid Kanan - Bawah Sorotan)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Status</label>
                    <select name="status" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inaktif</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Mulai Tayang (Opsional)</label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Akhir Tayang (Opsional)</label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('advertisements.index') }}" class="px-5 py-2.5 text-slate-600 dark:text-gray-300 font-medium hover:bg-slate-100 dark:hover:bg-gray-700 rounded-xl transition-all">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white font-semibold rounded-xl hover:bg-primary/90 transition-all">Simpan Iklan</button>
            </div>
        </form>
    </div>

    <!-- Wireframe Preview Card -->
    <div class="col-span-12 lg:col-span-5">
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-5 shadow-sm sticky top-6">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4">Preview Letak Iklan (Wireframe)</h3>
            
            <!-- Wireframe Mockup -->
            <div class="relative w-full border border-slate-200 dark:border-slate-750 rounded-xl overflow-hidden bg-white dark:bg-slate-900 p-3 space-y-3 text-[10px] font-semibold text-slate-400 select-none">
                
                <!-- Mock Header -->
                <div class="w-full h-8 bg-slate-100 dark:bg-slate-800 rounded flex items-center justify-between px-3 border border-slate-200 dark:border-slate-700">
                    <span class="font-black text-slate-600 dark:text-slate-400 text-[9px]">INFO-SEPUTAR62</span>
                    <div class="flex gap-2">
                        <span class="w-8 h-2 bg-slate-200 dark:bg-slate-700 rounded"></span>
                        <span class="w-8 h-2 bg-slate-200 dark:bg-slate-700 rounded"></span>
                    </div>
                </div>

                <!-- Main Body Grid -->
                <div class="grid grid-cols-12 gap-2 pt-1 min-h-[220px]">
                    <!-- Left Sidebar (Column 1) -->
                    <div class="col-span-3 bg-slate-50 dark:bg-slate-800 rounded p-1 border border-slate-200 dark:border-slate-700 flex flex-col gap-1.5">
                        <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-full flex items-center justify-center text-[6px]">Kurs</div>
                        <div id="preview-slot1" class="preview-slot h-7 bg-slate-55 dark:bg-slate-850 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300">
                            <span>SLOT 1</span>
                        </div>
                        <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-full flex items-center justify-center text-[6px]">Harga Emas</div>
                        <div id="preview-slot2" class="preview-slot h-7 bg-slate-55 dark:bg-slate-850 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300">
                            <span>SLOT 2</span>
                        </div>
                    </div>

                    <!-- Middle Main Content (Column 2) -->
                    <div class="col-span-6 bg-slate-50/50 dark:bg-slate-800/40 rounded p-1 border border-slate-200/60 dark:border-slate-700/60 flex flex-col gap-1.5">
                        <div class="h-6 bg-slate-200 dark:bg-slate-700 rounded flex items-center justify-center text-[6px]">Hero Slider</div>
                        
                        <div id="preview-slot3" class="preview-slot h-7 bg-slate-55 dark:bg-slate-850 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300">
                            <span>SLOT 3</span>
                        </div>

                        <div class="h-4 bg-slate-100 dark:bg-slate-700 rounded flex items-center justify-center text-[6px]">Artikel Grid</div>

                        <div id="preview-slot4" class="preview-slot h-7 bg-slate-55 dark:bg-slate-850 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300">
                            <span>SLOT 4</span>
                        </div>

                        <div class="h-4 bg-slate-100 dark:bg-slate-700 rounded flex items-center justify-center text-[6px]">Terkini Header</div>

                        <div id="preview-slot5" class="preview-slot h-7 bg-slate-55 dark:bg-slate-850 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300">
                            <span>SLOT 5</span>
                        </div>

                        <div class="h-4 bg-slate-100 dark:bg-slate-700 rounded flex items-center justify-center text-[6px]">Terkini List</div>

                        <div id="preview-slot6" class="preview-slot h-7 bg-slate-55 dark:bg-slate-850 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300">
                            <span>SLOT 6</span>
                        </div>

                        <div id="preview-slot7" class="preview-slot h-7 bg-slate-55 dark:bg-slate-850 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300">
                            <span>SLOT 7</span>
                        </div>
                    </div>

                    <!-- Right Sidebar (Column 3) -->
                    <div class="col-span-3 bg-slate-50 dark:bg-slate-800 rounded p-1 border border-slate-200 dark:border-slate-700 flex flex-col gap-1.5">
                        <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-full flex items-center justify-center text-[6px]">Terpopuler</div>
                        <div id="preview-slot8" class="preview-slot h-7 bg-slate-55 dark:bg-slate-850 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300">
                            <span>SLOT 8</span>
                        </div>

                        <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-full flex items-center justify-center text-[6px]">Topik</div>
                        <div id="preview-slot9" class="preview-slot h-7 bg-slate-55 dark:bg-slate-850 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300">
                            <span>SLOT 9</span>
                        </div>

                        <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-full flex items-center justify-center text-[6px]">Sorotan</div>
                        <div id="preview-slot10" class="preview-slot h-7 bg-slate-55 dark:bg-slate-850 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300">
                            <span>SLOT 10</span>
                        </div>
                    </div>
                </div>

                <!-- Mock Footer -->
                <div class="w-full h-6 bg-slate-100 dark:bg-slate-800 rounded flex items-center justify-center border border-slate-200 dark:border-slate-700 text-[8px]">
                    <span>FOOTER</span>
                </div>
            </div>

            <div class="mt-4 text-[11px] text-slate-500 dark:text-gray-400 text-center leading-relaxed">
                * Posisi terpilih disorot warna <span class="text-primary font-bold dark:text-primary/95">Biru</span>. Iklan akan dirender secara dinamis di sela-sela konten halaman utama.
            </div>
        </div>
    </div>
</div>

<style>
    .active-slot {
        background-color: rgb(239 246 255) !important; /* bg-blue-50 */
        border-color: rgb(59 130 246) !important; /* border-blue-500 */
        color: rgb(37 99 235) !important; /* text-blue-600 */
        border-style: solid !important;
    }
</style>

<script>
    function updateWireframeHighlight() {
        const select = document.querySelector('select[name="position"]');
        if (!select) return;
        const pos = select.value;
        
        // Reset all slots
        document.querySelectorAll('.preview-slot').forEach(el => {
            el.classList.remove('active-slot');
        });
        
        // Highlight active position
        const activeEl = document.getElementById('preview-' + pos);
        if (activeEl) {
            activeEl.classList.add('active-slot');
        }
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        const select = document.querySelector('select[name="position"]');
        if (select) {
            select.addEventListener('change', updateWireframeHighlight);
            updateWireframeHighlight();
        }
    });
</script>
@endsection
