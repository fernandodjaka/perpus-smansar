<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="{{ route('catalog.show', $book->id) }}" class="btn btn-sm btn-ghost" wire:navigate>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Tutup Sementara
                </a>
                @if(!auth()->user()->isAdmin())
                    <form action="{{ route('ebook.finish', $borrowId) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-lg inline-flex items-center gap-1.5 border border-emerald-500/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            Selesai Membaca
                        </button>
                    </form>
                @endif
            </div>
            <div class="text-center">
                <span class="text-xs uppercase tracking-widest text-indigo-500 font-bold">Membaca E-Book</span>
                <h4 class="font-bold text-slate-800 dark:text-white text-base">{{ $book->title }}</h4>
            </div>
            <div>
                <span class="badge badge-accent text-white font-bold">{{ $book->category->name }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-900 min-h-screen flex items-center justify-center">
        <div class="max-w-5xl w-full mx-auto sm:px-6 lg:px-8 space-y-4">
            
            <!-- Secure Ebook Info Alert -->
            <div class="bg-slate-800/80 backdrop-blur-md border border-slate-700/60 p-4 rounded-xl flex items-center justify-between text-slate-350 text-xs shadow-xl">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span>Mode pembacaan aman aktif. Klik kanan dan unduhan langsung dinonaktifkan untuk melindungi hak cipta buku sekolah.</span>
                </div>
                <span class="text-slate-500">Penulis: {{ $book->author }}</span>
            </div>

            <!-- PDF Viewer Iframe -->
            <div class="bg-slate-950 p-2 rounded-3xl border border-slate-800 shadow-2xl relative">
                <!-- Watermark Layer (Optional cosmetic styling) -->
                <div class="absolute inset-0 pointer-events-none flex items-center justify-center select-none opacity-[0.02]">
                    <span class="text-6xl font-bold font-mono text-white tracking-widest uppercase">E-LIBRARY {{ auth()->user()->student->nisn }}</span>
                </div>
                
                <iframe 
                    src="{{ asset('storage/' . $book->pdf_file) }}#toolbar=0&navpanes=0&statusbar=0" 
                    class="w-full h-[75vh] border-0 rounded-2xl shadow-inner select-none"
                    style="-webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;"
                    oncontextmenu="return false;"
                ></iframe>
            </div>

        </div>
    </div>
</div>
