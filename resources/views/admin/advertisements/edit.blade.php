@extends('layouts.admin')

@section('header', 'Edit Iklan')

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
        <form action="{{ route('advertisements.update', $advertisement->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Judul / Nama Iklan</label>
                <input type="text" name="title" required value="{{ old('title', $advertisement->title) }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white focus:ring-primary focus:border-primary px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Gambar / Banner</label>
                <div class="mb-3">
                    <img src="{{ $advertisement->image_url }}" alt="Preview" class="h-20 w-auto object-contain border rounded-lg bg-slate-50 dark:bg-gray-900 p-1">
                </div>
                <input type="file" name="image_path" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                <p class="text-xs text-slate-400 mt-1">Biarkan kosong jika tidak ingin mengubah gambar.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">URL / Link Tujuan (Opsional)</label>
                <input type="url" name="url" value="{{ old('url', $advertisement->url) }}" placeholder="https://" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white focus:ring-primary focus:border-primary px-4 py-2">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Posisi</label>
                    <select name="position" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2 font-semibold">
                        <option value="slot1" {{ old('position', $advertisement->position) == 'slot1' ? 'selected' : '' }} class="{{ in_array('slot1', $activeSlots ?? []) ? 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400 font-semibold' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 font-semibold' }}">
                            Slot 1 (Grid Kiri Atas - Kurs) {{ in_array('slot1', $activeSlots ?? []) ? '● (Aktif / Terisi)' : '○ (Tersedia)' }}
                        </option>
                        <option value="slot2" {{ old('position', $advertisement->position) == 'slot2' ? 'selected' : '' }} class="{{ in_array('slot2', $activeSlots ?? []) ? 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400 font-semibold' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 font-semibold' }}">
                            Slot 2 (Grid Kiri Bawah - Emas) {{ in_array('slot2', $activeSlots ?? []) ? '● (Aktif / Terisi)' : '○ (Tersedia)' }}
                        </option>
                        <option value="slot3" {{ old('position', $advertisement->position) == 'slot3' ? 'selected' : '' }} class="{{ in_array('slot3', $activeSlots ?? []) ? 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400 font-semibold' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 font-semibold' }}">
                            Slot 3 (Grid Tengah - Bawah Slider) {{ in_array('slot3', $activeSlots ?? []) ? '● (Aktif / Terisi)' : '○ (Tersedia)' }}
                        </option>
                        <option value="slot4" {{ old('position', $advertisement->position) == 'slot4' ? 'selected' : '' }} class="{{ in_array('slot4', $activeSlots ?? []) ? 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400 font-semibold' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 font-semibold' }}">
                            Slot 4 (Grid Tengah - Sela Card) {{ in_array('slot4', $activeSlots ?? []) ? '● (Aktif / Terisi)' : '○ (Tersedia)' }}
                        </option>
                        <option value="slot5" {{ old('position', $advertisement->position) == 'slot5' ? 'selected' : '' }} class="{{ in_array('slot5', $activeSlots ?? []) ? 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400 font-semibold' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 font-semibold' }}">
                            Slot 5 (Grid Tengah - Atas Terkini) {{ in_array('slot5', $activeSlots ?? []) ? '● (Aktif / Terisi)' : '○ (Tersedia)' }}
                        </option>
                        <option value="slot6" {{ old('position', $advertisement->position) == 'slot6' ? 'selected' : '' }} class="{{ in_array('slot6', $activeSlots ?? []) ? 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400 font-semibold' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 font-semibold' }}">
                            Slot 6 (Grid Tengah - Sela Terkini) {{ in_array('slot6', $activeSlots ?? []) ? '● (Aktif / Terisi)' : '○ (Tersedia)' }}
                        </option>
                        <option value="slot7" {{ old('position', $advertisement->position) == 'slot7' ? 'selected' : '' }} class="{{ in_array('slot7', $activeSlots ?? []) ? 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400 font-semibold' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 font-semibold' }}">
                            Slot 7 (Grid Tengah - Atas Footer) {{ in_array('slot7', $activeSlots ?? []) ? '● (Aktif / Terisi)' : '○ (Tersedia)' }}
                        </option>
                        <option value="slot8" {{ old('position', $advertisement->position) == 'slot8' ? 'selected' : '' }} class="{{ in_array('slot8', $activeSlots ?? []) ? 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400 font-semibold' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 font-semibold' }}">
                            Slot 8 (Grid Kanan - Bawah Terpopuler) {{ in_array('slot8', $activeSlots ?? []) ? '● (Aktif / Terisi)' : '○ (Tersedia)' }}
                        </option>
                        <option value="slot9" {{ old('position', $advertisement->position) == 'slot9' ? 'selected' : '' }} class="{{ in_array('slot9', $activeSlots ?? []) ? 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400 font-semibold' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 font-semibold' }}">
                            Slot 9 (Grid Kanan - Bawah Topik) {{ in_array('slot9', $activeSlots ?? []) ? '● (Aktif / Terisi)' : '○ (Tersedia)' }}
                        </option>
                        <option value="slot10" {{ old('position', $advertisement->position) == 'slot10' ? 'selected' : '' }} class="{{ in_array('slot10', $activeSlots ?? []) ? 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400 font-semibold' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 font-semibold' }}">
                            Slot 10 (Grid Kanan - Bawah Sorotan) {{ in_array('slot10', $activeSlots ?? []) ? '● (Aktif / Terisi)' : '○ (Tersedia)' }}
                        </option>
                    </select>
                </div>
                <div x-data="{ status: '{{ old('status', $advertisement->status) }}' }">
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Status</label>
                    <select name="status" x-model="status" 
                        class="w-full rounded-xl px-4 py-2 font-semibold border transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary/20"
                        :class="status === 'active' 
                            ? 'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-400 dark:border-emerald-900' 
                            : 'bg-red-50 text-red-800 border-red-200 dark:bg-red-950/30 dark:text-red-400 dark:border-red-900'">
                        <option value="active" class="bg-white dark:bg-gray-900 text-slate-900 dark:text-white font-semibold">Aktif</option>
                        <option value="inactive" class="bg-white dark:bg-gray-900 text-slate-900 dark:text-white font-semibold">Inaktif</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Mulai Tayang (Opsional)</label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date', $advertisement->start_date ? $advertisement->start_date->format('Y-m-d\TH:i') : '') }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Akhir Tayang (Opsional)</label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date', $advertisement->end_date ? $advertisement->end_date->format('Y-m-d\TH:i') : '') }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('advertisements.index') }}" class="px-5 py-2.5 text-slate-600 dark:text-gray-300 font-medium hover:bg-slate-100 dark:hover:bg-gray-700 rounded-xl transition-all">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white font-semibold rounded-xl hover:bg-primary/90 transition-all">Update Iklan</button>
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
                        <div id="preview-slot1" class="preview-slot h-7 border border-dashed rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300 {{ in_array('slot1', $activeSlots ?? []) ? 'occupied-slot' : 'bg-slate-55 dark:bg-slate-850 border-slate-300 dark:border-slate-600' }}">
                            <span>SLOT 1 {{ in_array('slot1', $activeSlots ?? []) ? '(AK)' : '' }}</span>
                        </div>
                        <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-full flex items-center justify-center text-[6px]">Harga Emas</div>
                        <div id="preview-slot2" class="preview-slot h-7 border border-dashed rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300 {{ in_array('slot2', $activeSlots ?? []) ? 'occupied-slot' : 'bg-slate-55 dark:bg-slate-850 border-slate-300 dark:border-slate-600' }}">
                            <span>SLOT 2 {{ in_array('slot2', $activeSlots ?? []) ? '(AK)' : '' }}</span>
                        </div>
                    </div>

                    <!-- Middle Main Content (Column 2) -->
                    <div class="col-span-6 bg-slate-50/50 dark:bg-slate-800/40 rounded p-1 border border-slate-200/60 dark:border-slate-700/60 flex flex-col gap-1.5">
                        <div class="h-6 bg-slate-200 dark:bg-slate-700 rounded flex items-center justify-center text-[6px]">Hero Slider</div>
                        
                        <div id="preview-slot3" class="preview-slot h-7 border border-dashed rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300 {{ in_array('slot3', $activeSlots ?? []) ? 'occupied-slot' : 'bg-slate-55 dark:bg-slate-850 border-slate-300 dark:border-slate-600' }}">
                            <span>SLOT 3 {{ in_array('slot3', $activeSlots ?? []) ? '(AK)' : '' }}</span>
                        </div>

                        <div class="h-4 bg-slate-100 dark:bg-slate-700 rounded flex items-center justify-center text-[6px]">Artikel Grid</div>

                        <div id="preview-slot4" class="preview-slot h-7 border border-dashed rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300 {{ in_array('slot4', $activeSlots ?? []) ? 'occupied-slot' : 'bg-slate-55 dark:bg-slate-850 border-slate-300 dark:border-slate-600' }}">
                            <span>SLOT 4 {{ in_array('slot4', $activeSlots ?? []) ? '(AK)' : '' }}</span>
                        </div>

                        <div class="h-4 bg-slate-100 dark:bg-slate-700 rounded flex items-center justify-center text-[6px]">Terkini Header</div>

                        <div id="preview-slot5" class="preview-slot h-7 border border-dashed rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300 {{ in_array('slot5', $activeSlots ?? []) ? 'occupied-slot' : 'bg-slate-55 dark:bg-slate-850 border-slate-300 dark:border-slate-600' }}">
                            <span>SLOT 5 {{ in_array('slot5', $activeSlots ?? []) ? '(AK)' : '' }}</span>
                        </div>

                        <div class="h-4 bg-slate-100 dark:bg-slate-700 rounded flex items-center justify-center text-[6px]">Terkini List</div>

                        <div id="preview-slot6" class="preview-slot h-7 border border-dashed rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300 {{ in_array('slot6', $activeSlots ?? []) ? 'occupied-slot' : 'bg-slate-55 dark:bg-slate-850 border-slate-300 dark:border-slate-600' }}">
                            <span>SLOT 6 {{ in_array('slot6', $activeSlots ?? []) ? '(AK)' : '' }}</span>
                        </div>

                        <div id="preview-slot7" class="preview-slot h-7 border border-dashed rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300 {{ in_array('slot7', $activeSlots ?? []) ? 'occupied-slot' : 'bg-slate-55 dark:bg-slate-850 border-slate-300 dark:border-slate-600' }}">
                            <span>SLOT 7 {{ in_array('slot7', $activeSlots ?? []) ? '(AK)' : '' }}</span>
                        </div>
                    </div>

                    <!-- Right Sidebar (Column 3) -->
                    <div class="col-span-3 bg-slate-50 dark:bg-slate-800 rounded p-1 border border-slate-200 dark:border-slate-700 flex flex-col gap-1.5">
                        <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-full flex items-center justify-center text-[6px]">Terpopuler</div>
                        <div id="preview-slot8" class="preview-slot h-7 border border-dashed rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300 {{ in_array('slot8', $activeSlots ?? []) ? 'occupied-slot' : 'bg-slate-55 dark:bg-slate-850 border-slate-300 dark:border-slate-600' }}">
                            <span>SLOT 8 {{ in_array('slot8', $activeSlots ?? []) ? '(AK)' : '' }}</span>
                        </div>

                        <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-full flex items-center justify-center text-[6px]">Topik</div>
                        <div id="preview-slot9" class="preview-slot h-7 border border-dashed rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300 {{ in_array('slot9', $activeSlots ?? []) ? 'occupied-slot' : 'bg-slate-55 dark:bg-slate-850 border-slate-300 dark:border-slate-600' }}">
                            <span>SLOT 9 {{ in_array('slot9', $activeSlots ?? []) ? '(AK)' : '' }}</span>
                        </div>

                        <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-full flex items-center justify-center text-[6px]">Sorotan</div>
                        <div id="preview-slot10" class="preview-slot h-7 border border-dashed rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300 {{ in_array('slot10', $activeSlots ?? []) ? 'occupied-slot' : 'bg-slate-55 dark:bg-slate-850 border-slate-300 dark:border-slate-600' }}">
                            <span>SLOT 10 {{ in_array('slot10', $activeSlots ?? []) ? '(AK)' : '' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Mock Footer -->
                <div class="w-full h-6 bg-slate-100 dark:bg-slate-800 rounded flex items-center justify-center border border-slate-200 dark:border-slate-700 text-[8px]">
                    <span>FOOTER</span>
                </div>
            </div>

            <div class="mt-4 text-[11px] text-slate-550 dark:text-gray-400 space-y-1.5 leading-relaxed">
                <div>* Posisi terpilih disorot warna <span class="text-primary font-bold dark:text-primary/95">Biru</span>.</div>
                <div>* Posisi dengan tanda <span class="text-red-500 font-bold">AK (Aktif / Terisi)</span> berwarna merah redup menunjukkan slot tersebut sudah terisi iklan aktif.</div>
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
    .occupied-slot {
        background-color: rgba(239, 68, 68, 0.08) !important;
        border-color: rgba(239, 68, 68, 0.35) !important;
        color: rgb(239, 68, 68) !important;
        border-style: dotted !important;
    }
</style>

<script>
    function updateWireframeHighlight() {
        const select = document.querySelector('select[name="position"]');
        if (!select) return;
        const pos = select.value;
        
        // Reset all active highlights (but keep occupied styles)
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
