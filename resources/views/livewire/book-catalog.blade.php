<div>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Katalog Buku Perpustakaan') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Glassmorphic Search & Filter Bar -->
            <div class="card bg-white/70 dark:bg-slate-800/70 backdrop-blur-md border border-slate-100 dark:border-slate-700 shadow-xl">
                <div class="card-body p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search Box -->
                        <div class="md:col-span-2 relative">
                            <label class="input input-bordered flex items-center gap-2 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari judul buku, penulis, atau ISBN..." class="grow border-none focus:outline-none focus:ring-0 p-0 text-slate-800 dark:text-white bg-transparent" />
                            </label>
                        </div>
                        
                        <!-- Category Filter -->
                        <div>
                            <select wire:model.live="category" class="select select-bordered w-full bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Type Filter -->
                        <div>
                            <select wire:model.live="type" class="select select-bordered w-full bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200">
                                <option value="">Semua Format</option>
                                <option value="physical">Buku Fisik</option>
                                <option value="ebook">E-Book (Digital)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Books Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($books as $book)
                    <div class="card bg-white/70 dark:bg-slate-800/70 backdrop-blur-md border border-slate-100 dark:border-slate-700 shadow-lg hover:scale-105 transition-all duration-300 overflow-hidden flex flex-col justify-between group">
                        <!-- Book Cover Image -->
                        <figure class="px-3 pt-3 h-56 overflow-hidden bg-slate-100 dark:bg-slate-900 relative">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/books/covers/' . $book->cover_image) }}" alt="{{ $book->title }}" class="object-cover h-full w-full rounded-xl group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-indigo-500/40 font-bold uppercase select-none w-full gap-2">
                                    <svg class="w-12 h-12 text-slate-300 dark:text-slate-655" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <span class="text-xs">{{ substr($book->title, 0, 15) }}...</span>
                                </div>
                            @endif
                            
                            <!-- Badges -->
                            <div class="absolute top-5 right-5 flex flex-col gap-1">
                                @if($book->isEbook())
                                    <span class="badge badge-accent text-white font-bold text-[10px] py-1.5 shadow-md">E-Book</span>
                                @else
                                    <span class="badge badge-indigo text-white font-bold text-[10px] py-1.5 shadow-md">Fisik</span>
                                @endif
                                
                                @if(!$book->isAvailable())
                                    <span class="badge badge-error text-white font-bold text-[10px] py-1.5 shadow-md">Habis</span>
                                @endif
                            </div>
                        </figure>

                        <!-- Book Details -->
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] uppercase font-bold text-indigo-500 tracking-wider">{{ $book->category->name }}</span>
                                    <span class="text-xs text-slate-400 font-medium">Rak: {{ $book->shelf->name }}</span>
                                </div>
                                <h4 class="font-bold text-slate-800 dark:text-white text-base line-clamp-1 mt-1.5 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">{{ $book->title }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Penulis: {{ $book->author }}</p>
                                <p class="text-[11px] text-slate-400 mt-2 line-clamp-2">{{ $book->description }}</p>
                            </div>

                            <div class="mt-4 border-t border-slate-100 dark:border-slate-700/60 pt-3 flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-slate-400">Rating</span>
                                    <span class="text-xs font-bold text-amber-500 flex items-center gap-0.5">
                                        ★ {{ number_format($book->averageRating(), 1) }}
                                    </span>
                                </div>

                                <div class="flex flex-col items-end">
                                    @if($book->isEbook())
                                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold">Tersedia Digital</span>
                                    @else
                                        <span class="text-[10px] text-slate-400">Stok</span>
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $book->stock }} eks</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('catalog.show', $book->id) }}" class="btn btn-sm btn-primary w-full shadow-md text-xs" wire:navigate>
                                    Lihat Detail & Pinjam
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full card bg-white/70 dark:bg-slate-800/70 backdrop-blur-md p-12 text-center text-slate-500 dark:text-slate-400">
                        <svg class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h4 class="text-lg font-bold">Buku Tidak Ditemukan</h4>
                        <p class="text-xs mt-1">Coba gunakan kata kunci pencarian yang lain atau hapus filter kategori.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-6">
                {{ $books->links() }}
            </div>
        </div>
    </div>
</div>
