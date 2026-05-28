@extends('layouts.admin')

@section('header', 'Edit Iklan')

@section('content')
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
                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah gambar.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">URL / Link Tujuan (Opsional)</label>
                <input type="url" name="url" value="{{ old('url', $advertisement->url) }}" placeholder="https://" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white focus:ring-primary focus:border-primary px-4 py-2">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Posisi</label>
                    <select name="position" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
                        <option value="header" {{ $advertisement->position === 'header' ? 'selected' : '' }}>Iklan Banner Atas (Header Leaderboard)</option>
                        <option value="sidebar_mid" {{ $advertisement->position === 'sidebar_mid' ? 'selected' : '' }}>Iklan Sayap Kiri Atas (160x380)</option>
                        <option value="article_mid" {{ $advertisement->position === 'article_mid' ? 'selected' : '' }}>Iklan Sayap Kiri Bawah (160x204)</option>
                        <option value="sidebar_top" {{ $advertisement->position === 'sidebar_top' ? 'selected' : '' }}>Iklan Sayap Kanan Atas (160x204)</option>
                        <option value="article_bottom" {{ $advertisement->position === 'article_bottom' ? 'selected' : '' }}>Iklan Sayap Kanan Bawah (160x380)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Status</label>
                    <select name="status" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
                        <option value="active" {{ $advertisement->status === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ $advertisement->status === 'inactive' ? 'selected' : '' }}>Inaktif</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Mulai Tayang (Opsional)</label>
                    <input type="datetime-local" name="start_date" value="{{ $advertisement->start_date ? $advertisement->start_date->format('Y-m-d\TH:i') : '' }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-gray-200 mb-1">Akhir Tayang (Opsional)</label>
                    <input type="datetime-local" name="end_date" value="{{ $advertisement->end_date ? $advertisement->end_date->format('Y-m-d\TH:i') : '' }}" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white px-4 py-2">
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
                    <span class="font-black text-slate-600 dark:text-slate-400">INFO-SEPUTAR62</span>
                    <div class="flex gap-2">
                        <span class="w-8 h-2 bg-slate-200 dark:bg-slate-700 rounded"></span>
                        <span class="w-8 h-2 bg-slate-200 dark:bg-slate-700 rounded"></span>
                    </div>
                </div>

                <!-- Mock Banner Ad (Header) -->
                <div id="preview-header" class="preview-slot w-full h-8 bg-slate-50 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center transition-all duration-300">
                    <span>IKLAN BANNER ATAS</span>
                </div>

                <!-- Main Body Wrapper (Relative to show wings) -->
                <div class="relative grid grid-cols-12 gap-2 pt-1 min-h-[180px] px-8">
                    
                    <!-- Left Wing Ads -->
                    <div class="absolute left-0 top-0 bottom-0 w-6 flex flex-col gap-2 pointer-events-none">
                        <div id="preview-sidebar_mid" class="preview-slot h-[70px] bg-slate-50 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300">
                            <span>SAYAP KIRI TOP</span>
                        </div>
                        <div id="preview-article_mid" class="preview-slot h-[50px] bg-slate-50 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300">
                            <span>SAYAP KIRI BTM</span>
                        </div>
                    </div>

                    <!-- Left Sidebar -->
                    <div id="preview-col-left" class="col-span-3 bg-slate-50 dark:bg-slate-800 rounded p-1.5 border border-slate-200 dark:border-slate-700 flex flex-col gap-1.5">
                        <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded w-2/3"></div>
                        <div class="h-1 bg-slate-100 dark:bg-slate-700 rounded w-full"></div>
                        <div class="h-1 bg-slate-100 dark:bg-slate-700 rounded w-full"></div>
                    </div>

                    <!-- Middle Main Content -->
                    <div id="preview-col-main" class="col-span-6 bg-slate-50/50 dark:bg-slate-800/40 rounded p-1.5 border border-slate-200/60 dark:border-slate-700/60 flex flex-col gap-2">
                        <!-- Inline Content Ads Top on Mobile/Tablet -->
                        <div id="preview-inline-top" class="preview-slot h-6 bg-slate-50 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[6px] text-center p-0.5 transition-all duration-300 hidden">
                            <span>IN-CONTENT (KIRI TOP)</span>
                        </div>

                        <!-- Featured Slider -->
                        <div class="h-10 bg-slate-200 dark:bg-slate-700 rounded flex items-center justify-center">
                            <span class="text-[7px]">Konten</span>
                        </div>
                        
                        <!-- Inline Content Ads on Mobile/Tablet -->
                        <div id="preview-inline-mid" class="preview-slot h-6 bg-slate-50 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[6px] text-center p-0.5 transition-all duration-300 hidden">
                            <span>IN-CONTENT (KIRI BTM)</span>
                        </div>

                        <div class="space-y-1">
                            <div class="h-1 bg-slate-200 dark:bg-slate-700 rounded w-full"></div>
                            <div class="h-1 bg-slate-200 dark:bg-slate-700 rounded w-5/6"></div>
                        </div>

                        <!-- Inline Content Ads Bottom on Mobile/Tablet -->
                        <div id="preview-inline-bottom" class="preview-slot h-6 bg-slate-50 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[6px] text-center p-0.5 transition-all duration-300 hidden">
                            <span>IN-CONTENT (KANAN BTM)</span>
                        </div>
                    </div>

                    <!-- Right Sidebar -->
                    <div id="preview-col-right" class="col-span-3 bg-slate-50 dark:bg-slate-800 rounded p-1.5 border border-slate-200 dark:border-slate-700 flex flex-col gap-1.5">
                        <!-- Sidebar Top Ad -->
                        <div id="preview-sidebar_top" class="preview-slot h-8 bg-slate-50 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[6px] text-center p-0.5 transition-all duration-300 hidden">
                            <span>SIDEBAR TOP</span>
                        </div>
                        <div class="h-1.5 bg-slate-200 dark:bg-slate-700 rounded w-3/4"></div>
                        <div class="h-1 bg-slate-100 dark:bg-slate-700 rounded w-full"></div>
                    </div>

                    <!-- Right Wing Ads -->
                    <div class="absolute right-0 top-0 bottom-0 w-6 flex flex-col gap-2 pointer-events-none">
                        <div id="preview-right_wing_top" class="preview-slot h-[50px] bg-slate-50 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300">
                            <span>SAYAP KANAN TOP</span>
                        </div>
                        <div id="preview-article_bottom" class="preview-slot h-[70px] bg-slate-50 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded flex items-center justify-center text-[7px] text-center p-0.5 transition-all duration-300">
                            <span>SAYAP KANAN BTM</span>
                        </div>
                    </div>

                </div>

                <!-- Mock Footer -->
                <div class="w-full h-6 bg-slate-100 dark:bg-slate-800 rounded flex items-center justify-center border border-slate-200 dark:border-slate-700 text-[8px]">
                    <span>FOOTER</span>
                </div>

            </div>

            <!-- Responsive toggle buttons in preview -->
            <div class="mt-4 flex justify-center gap-2">
                <button type="button" id="btn-mock-desktop" onclick="toggleMockView('desktop')" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-primary text-white shadow-sm border border-transparent">
                    Desktop
                </button>
                <button type="button" id="btn-mock-mobile" onclick="toggleMockView('mobile')" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-750 hover:bg-slate-50 dark:hover:bg-slate-750">
                    Mobile / Tablet
                </button>
            </div>
            
            <div class="mt-3 text-[11px] text-slate-500 dark:text-gray-400 text-center leading-relaxed">
                * Posisi terpilih disorot warna <span class="text-primary font-bold dark:text-primary/95">Biru</span>. Iklan sayap otomatis diposisikan ke dalam konten (*in-content*) pada perangkat Mobile/Tablet.
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
    .dark .active-slot {
        background-color: rgba(30, 58, 138, 0.3) !important; /* dark:bg-blue-900/30 */
        border-color: rgb(14 165 233) !important; /* dark:border-sky-500 */
        color: rgb(56 189 248) !important; /* dark:text-sky-400 */
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
        
        // Helper to highlight a slot
        function highlight(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.add('active-slot');
            }
        }
        
        // Highlight active position
        if (pos === 'header') {
            highlight('preview-header');
        } else if (pos === 'sidebar_mid') {
            highlight('preview-sidebar_mid');
            highlight('preview-inline-top');
        } else if (pos === 'article_mid') {
            highlight('preview-article_mid');
            highlight('preview-inline-mid');
        } else if (pos === 'sidebar_top') {
            highlight('preview-sidebar_top');
            highlight('preview-right_wing_top');
        } else if (pos === 'article_bottom') {
            highlight('preview-article_bottom');
            highlight('preview-inline-bottom');
        }
    }
    
    function toggleMockView(mode) {
        console.log("toggleMockView called with mode:", mode);
        try {
            const desktopBtn = document.getElementById('btn-mock-desktop');
            const mobileBtn = document.getElementById('btn-mock-mobile');
            
            // Slots that are shown on desktop only (wings)
            const leftWing = document.getElementById('preview-sidebar_mid').parentElement;
            const rightWing = document.getElementById('preview-right_wing_top').parentElement;
            
            // Slots that show inline/sidebar on mobile
            const inlineTop = document.getElementById('preview-inline-top');
            const inlineMid = document.getElementById('preview-inline-mid');
            const inlineBottom = document.getElementById('preview-inline-bottom');
            const sidebarTop = document.getElementById('preview-sidebar_top');
            
            // Mock columns
            const colLeft = document.getElementById('preview-col-left');
            const colMain = document.getElementById('preview-col-main');
            const colRight = document.getElementById('preview-col-right');
            
            console.log("Elements found:", {leftWing, rightWing, inlineTop, inlineMid, inlineBottom, sidebarTop, colLeft, colMain, colRight});

            if (mode === 'desktop') {
                desktopBtn.className = 'px-3 py-1.5 text-xs font-semibold rounded-lg bg-primary text-white shadow-sm border border-transparent';
                mobileBtn.className = 'px-3 py-1.5 text-xs font-semibold rounded-lg bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-750 hover:bg-slate-50 dark:hover:bg-slate-750';
                
                // Show wings
                leftWing.classList.remove('hidden');
                rightWing.classList.remove('hidden');
                
                // Hide mobile slots
                inlineTop.classList.add('hidden');
                inlineMid.classList.add('hidden');
                inlineBottom.classList.add('hidden');
                sidebarTop.classList.add('hidden');
                
                // Reset columns for desktop
                colLeft.classList.remove('hidden');
                colLeft.style.gridColumn = "span 3 / span 3";
                colMain.style.gridColumn = "span 6 / span 6";
                colRight.style.gridColumn = "span 3 / span 3";
            } else {
                mobileBtn.className = 'px-3 py-1.5 text-xs font-semibold rounded-lg bg-primary text-white shadow-sm border border-transparent';
                desktopBtn.className = 'px-3 py-1.5 text-xs font-semibold rounded-lg bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-750 hover:bg-slate-50 dark:hover:bg-slate-750';
                
                // Hide wings
                leftWing.classList.add('hidden');
                rightWing.classList.add('hidden');
                
                // Show mobile slots
                inlineTop.classList.remove('hidden');
                inlineMid.classList.remove('hidden');
                inlineBottom.classList.remove('hidden');
                sidebarTop.classList.remove('hidden');
                
                // Adjust columns for mobile/tablet
                colLeft.classList.add('hidden');
                colMain.style.gridColumn = "span 12 / span 12";
                colRight.style.gridColumn = "span 12 / span 12";
                console.log("Applied mobile spans to columns. Main:", colMain.style.gridColumn, "Right:", colRight.style.gridColumn);
            }
            
            updateWireframeHighlight();
        } catch (e) {
            console.error("Error in toggleMockView:", e);
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
