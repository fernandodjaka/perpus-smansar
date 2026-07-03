<div>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('catalog.index') }}" class="btn btn-sm btn-ghost" wire:navigate>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Katalog
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Success/Error Alert -->
            @if($message)
                <div class="alert {{ $messageType === 'success' ? 'alert-success text-white' : 'alert-error text-white' }} shadow-lg">
                    <div>
                        <span class="font-bold">{{ $message }}</span>
                    </div>
                    <button class="btn btn-xs btn-circle btn-ghost" wire:click="$set('message', '')">✕</button>
                </div>
            @endif

            <!-- Main Book Information Card -->
            <div class="card lg:card-side bg-white/70 dark:bg-slate-800/70 backdrop-blur-md border border-slate-100 dark:border-slate-700 shadow-xl overflow-hidden">
                <!-- Book Cover Side -->
                <figure class="lg:w-2/5 bg-slate-100 dark:bg-slate-900/60 p-8 flex justify-center items-center relative">
                    <div class="w-64 h-96 shadow-2xl rounded-2xl overflow-hidden relative group">
                        @if($book->cover_image)
                            <img src="{{ asset('storage/books/covers/' . $book->cover_image) }}" alt="{{ $book->title }}" class="object-cover w-full h-full">
                        @else
                            <div class="flex flex-col items-center justify-center h-full bg-slate-200 dark:bg-slate-800 text-indigo-500 font-bold uppercase select-none gap-2">
                                <svg class="w-16 h-16 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span>Buku</span>
                            </div>
                        @endif
                        
                        <!-- Format Badge -->
                        <span class="absolute top-4 right-4 badge {{ $book->isEbook() ? 'badge-accent' : 'badge-indigo' }} text-white font-bold py-2 shadow-md">
                            {{ $book->isEbook() ? 'E-Book' : 'Buku Fisik' }}
                        </span>
                    </div>
                </figure>

                <!-- Book Information Side -->
                <div class="card-body lg:w-3/5 p-8 justify-between">
                    <div>
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="badge badge-outline border-indigo-500 text-indigo-600 dark:text-indigo-400 font-bold text-xs uppercase">{{ $book->category->name }}</span>
                                <h3 class="text-3xl font-extrabold text-slate-850 dark:text-white mt-2 leading-tight">{{ $book->title }}</h3>
                                <p class="text-lg text-slate-500 dark:text-slate-400 font-medium mt-1">Oleh: <span class="text-indigo-600 dark:text-indigo-400">{{ $book->author }}</span></p>
                            </div>
                        </div>

                        <!-- Info grid -->
                        <div class="grid grid-cols-2 gap-4 my-6 bg-slate-50/50 dark:bg-slate-900/30 p-4 rounded-2xl border border-slate-100 dark:border-slate-800">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">ISBN</span>
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-350">{{ $book->isbn }}</p>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Penerbit</span>
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-350">{{ $book->publisher }} ({{ $book->publication_year }})</p>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Jumlah Halaman</span>
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-350">{{ $book->total_pages }} halaman</p>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Lokasi Rak</span>
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-350">{{ $book->shelf->name }}</p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <h4 class="font-bold text-slate-800 dark:text-white">Sinopsis / Deskripsi</h4>
                            <p class="text-sm text-slate-600 dark:text-slate-350 leading-relaxed">{{ $book->description }}</p>
                        </div>
                    </div>

                    <!-- Call to action actions -->
                    <div class="mt-8 border-t border-slate-100 dark:border-slate-700/60 pt-6 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-1.5 bg-amber-50 dark:bg-amber-950/20 px-3 py-1.5 rounded-xl border border-amber-100 dark:border-amber-900/40">
                            <span class="text-lg text-amber-500">★</span>
                            <span class="text-sm font-bold text-slate-750 dark:text-slate-300">{{ number_format($book->averageRating(), 1) }}</span>
                            <span class="text-xs text-slate-400 font-medium">({{ $reviews->count() }} ulasan)</span>
                        </div>

                        <div class="flex-1 flex justify-end gap-3">
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.books') }}" class="btn btn-outline btn-neutral">
                                    Edit di Kelola Buku
                                </a>
                            @else
                                @if($book->isEbook())
                                    <button wire:click="borrowEbook" class="btn btn-accent text-white px-8 font-semibold shadow-lg shadow-emerald-500/25">
                                        Baca E-Book Sekarang
                                    </button>
                                @else
                                    @if($book->stock > 0)
                                        <button wire:click="requestReservation" class="btn btn-primary px-8 font-semibold shadow-lg shadow-indigo-500/20">
                                            Ajukan Peminjaman Fisik
                                        </button>
                                    @else
                                        <button wire:click="requestReservation" class="btn btn-warning text-white px-8 font-semibold">
                                            Masuk Antrean Reservasi
                                        </button>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Left Side: Review Input Form (Only for Student) -->
                <div class="md:col-span-1">
                    @if(!auth()->user()->isAdmin())
                        <div class="card bg-white/70 dark:bg-slate-800/70 backdrop-blur-md border border-slate-100 dark:border-slate-700 shadow-xl">
                            <div class="card-body p-6 space-y-4">
                                <h4 class="font-bold text-lg text-slate-850 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-2">Berikan Ulasan Anda</h4>
                                
                                <form wire:submit.prevent="submitReview" class="space-y-4">
                                    <!-- Rating Star Select -->
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-semibold text-slate-650 dark:text-slate-300">Rating Buku</span>
                                        </label>
                                        <div class="rating rating-md">
                                            <input type="radio" wire:model="rating" name="rating-2" class="mask mask-star-2 bg-amber-400" value="1" />
                                            <input type="radio" wire:model="rating" name="rating-2" class="mask mask-star-2 bg-amber-400" value="2" />
                                            <input type="radio" wire:model="rating" name="rating-2" class="mask mask-star-2 bg-amber-400" value="3" />
                                            <input type="radio" wire:model="rating" name="rating-2" class="mask mask-star-2 bg-amber-400" value="4" />
                                            <input type="radio" wire:model="rating" name="rating-2" class="mask mask-star-2 bg-amber-400" value="5" checked />
                                        </div>
                                    </div>

                                    <!-- Review Text Area -->
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-semibold text-slate-650 dark:text-slate-300">Tulis Ulasan</span>
                                        </label>
                                        <textarea wire:model="reviewText" class="textarea textarea-bordered h-24 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-250 text-sm focus:outline-none" placeholder="Tuliskan pendapat Anda tentang buku ini..."></textarea>
                                        @error('reviewText') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <button type="submit" class="btn btn-indigo btn-sm w-full font-bold text-white shadow-md">Kirim Ulasan</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="card bg-slate-100 dark:bg-slate-900/40 p-6 text-center text-slate-400">
                            <span class="text-xs">Ulasan buku hanya dapat ditulis oleh siswa/anggota perpustakaan.</span>
                        </div>
                    @endif
                </div>

                <!-- Right Side: List of Reviews -->
                <div class="md:col-span-2 space-y-4">
                    <h4 class="font-bold text-lg text-slate-800 dark:text-white">Daftar Ulasan Siswa</h4>
                    
                    @forelse($reviews as $rev)
                        <div class="card bg-white/60 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-700/60 shadow-md">
                            <div class="card-body p-5">
                                <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-700/40 pb-2 mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-indigo-500 text-white flex items-center justify-center font-bold text-sm uppercase">
                                            {{ substr($rev->student->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h5 class="font-semibold text-sm text-slate-800 dark:text-white leading-tight">{{ $rev->student->user->name }}</h5>
                                            <span class="text-[10px] text-slate-400">{{ $rev->student->class }} • {{ $rev->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <div class="rating rating-xs flex gap-0.5">
                                        @for($i=1; $i<=5; $i++)
                                            <span class="text-xs {{ $i <= $rev->rating ? 'text-amber-400' : 'text-slate-300' }}">★</span>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-xs text-slate-650 dark:text-slate-350 leading-relaxed">{{ $rev->review_text }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="card bg-white/50 dark:bg-slate-800/50 p-8 text-center text-slate-400">
                            <span class="text-sm">Belum ada ulasan untuk buku ini. Jadilah yang pertama memberikan ulasan!</span>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
