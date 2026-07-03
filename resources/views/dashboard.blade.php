<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="alert alert-success text-white shadow-lg">
                    <div>
                        <span class="font-bold">✓ {{ session('success') }}</span>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error text-white shadow-lg">
                    <div>
                        <span class="font-bold">✕ {{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if (auth()->user()->isAdmin())
                <!-- ==================== ADMIN DASHBOARD ==================== -->
                <!-- Stats Section -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Stat 1: Total Buku -->
                    <div class="card bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xl hover:scale-105 transition-all duration-300">
                        <div class="card-body flex-row items-center justify-between p-6">
                            <div>
                                <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Total Koleksi Buku</p>
                                <h3 class="text-3xl font-bold text-slate-800 dark:text-white mt-1">{{ $totalBooks }}</h3>
                            </div>
                            <div class="p-3 bg-indigo-50 dark:bg-indigo-950/50 rounded-2xl text-indigo-600 dark:text-indigo-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Stat 2: Peminjaman Aktif -->
                    <div class="card bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xl hover:scale-105 transition-all duration-300">
                        <div class="card-body flex-row items-center justify-between p-6">
                            <div>
                                <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Peminjaman Aktif</p>
                                <h3 class="text-3xl font-bold text-slate-800 dark:text-white mt-1">{{ $activeBorrows }}</h3>
                            </div>
                            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/50 rounded-2xl text-emerald-600 dark:text-emerald-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Stat 3: Antrean Reservasi -->
                    <div class="card bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xl hover:scale-105 transition-all duration-300">
                        <div class="card-body flex-row items-center justify-between p-6">
                            <div>
                                <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Antrean Reservasi</p>
                                <h3 class="text-3xl font-bold text-slate-800 dark:text-white mt-1">{{ $activeReservations }}</h3>
                            </div>
                            <div class="p-3 bg-amber-50 dark:bg-amber-950/50 rounded-2xl text-amber-600 dark:text-amber-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Stat 4: Total Siswa -->
                    <div class="card bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xl hover:scale-105 transition-all duration-300">
                        <div class="card-body flex-row items-center justify-between p-6">
                            <div>
                                <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Total Siswa Terdaftar</p>
                                <h3 class="text-3xl font-bold text-slate-800 dark:text-white mt-1">{{ \App\Models\Student::count() }}</h3>
                            </div>
                            <div class="p-3 bg-violet-50 dark:bg-violet-950/50 rounded-2xl text-violet-600 dark:text-violet-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Action Links -->
                <div class="flex gap-4">
                    <a href="{{ route('admin.circulation') }}" class="btn btn-primary shadow-lg shadow-indigo-500/20">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        Sirkulasi Baru
                    </a>
                    <a href="{{ route('admin.books') }}" class="btn btn-outline btn-secondary">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Kelola Koleksi Buku
                    </a>
                </div>

                <div class="card bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xl overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                        <h4 class="font-bold text-lg text-slate-800 dark:text-white">Aktivitas Sirkulasi Terbaru</h4>
                        <span class="badge badge-outline">Terkini</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700">
                                    <th>Siswa</th>
                                    <th>Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Batas Waktu</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBorrows as $borrow)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-750 border-b border-slate-100 dark:border-slate-700">
                                        <td>
                                            <div class="font-bold">{{ $borrow->student->user->name }}</div>
                                            <div class="text-xs opacity-50">NISN: {{ $borrow->student->nisn }}</div>
                                        </td>
                                        <td>
                                            <div class="font-semibold">{{ $borrow->book->title }}</div>
                                            <div class="text-xs opacity-50">ISBN: {{ $borrow->book->isbn }}</div>
                                        </td>
                                        <td>{{ $borrow->borrow_date->format('d M Y') }}</td>
                                        <td>{{ $borrow->due_date->format('d M Y') }}</td>
                                        <td class="whitespace-nowrap">
                                            @if($borrow->status === 'returned')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    Dikembalikan
                                                </span>
                                            @elseif($borrow->status === 'late')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Terlambat
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                                                    Dipinjam
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-6 opacity-40">Belum ada aktivitas peminjaman.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            @else
                <!-- ==================== STUDENT DASHBOARD ==================== -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left: Profile & Card -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Digital Library Card -->
                        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-tr from-indigo-600 to-violet-800 dark:from-indigo-900 dark:to-purple-950 p-6 text-white shadow-2xl flex flex-col justify-between h-64 hover:scale-[1.02] transition-transform duration-300">
                            <!-- Background pattern -->
                            <div class="absolute -right-16 -top-16 w-48 h-48 rounded-full bg-white/10 blur-xl"></div>
                            <div class="absolute -left-12 -bottom-12 w-40 h-40 rounded-full bg-white/10 blur-xl"></div>

                            <div class="flex justify-between items-start z-10">
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-indigo-200">Kartu Anggota Digital</p>
                                    <h4 class="font-bold text-lg mt-1 text-white">PERPUSTAKAAN SEKOLAH</h4>
                                </div>
                                <svg class="w-8 h-8 text-white/50" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2A10 10 0 0 0 2 12a9.9 9.9 0 0 0 2.29 6.42 1 1 0 0 0 .1-.1 7 7 0 0 1 15.22 0 1 1 0 0 0 .1.1A10 10 0 0 0 22 12 10 10 0 0 0 12 2zm0 4a3 3 0 1 1-3 3 3 3 0 0 1 3-3zm0 12a5 5 0 0 0-4.27-2.4 5 5 0 0 0 8.54 0A5 5 0 0 0 12 18z"></path></svg>
                            </div>

                            <div class="z-10">
                                <h3 class="text-xl font-bold tracking-wide">{{ auth()->user()->name }}</h3>
                                <p class="text-xs text-indigo-200 mt-1">NISN: {{ auth()->user()->student?->nisn }}</p>
                                <p class="text-xs text-indigo-250 font-medium">Kelas: {{ auth()->user()->student?->class }}</p>
                            </div>

                            <div class="flex justify-between items-end border-t border-white/20 pt-4 z-10">
                                <div class="bg-white px-3 py-1 rounded text-black text-xs font-mono select-none">
                                    |||||| | |||| ||| {{ auth()->user()->student?->nisn }}
                                </div>
                                <span class="text-[10px] text-indigo-200 uppercase font-semibold">Aktif</span>
                            </div>
                        </div>

                        <!-- Mini Stats Card -->
                        <div class="card bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xl">
                            <div class="card-body p-6 space-y-4">
                                <h4 class="font-bold text-slate-800 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-2">Status Peminjaman</h4>

                                <div class="flex justify-between items-center">
                                    <span class="text-sm opacity-60">Sedang Dipinjam</span>
                                    <span class="badge badge-info text-white font-bold">{{ $activeBorrowsCount }} Buku</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm opacity-60">Antrean Reservasi</span>
                                    <span class="badge badge-warning text-white font-bold">{{ $activeReservationsCount }} Buku</span>
                                </div>
                                <div class="flex justify-between items-center border-t border-slate-200 dark:border-slate-700 pt-3">
                                    <span class="text-sm opacity-60">Status Layanan</span>
                                    <span class="badge badge-success font-bold">✓ Gratis</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Borrowing list & Recommendation -->
                    <div class="lg:col-span-2 space-y-8">
                        
                        <!-- Peminjaman Aktif Saya -->
                        <div class="card bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xl overflow-hidden">
                            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                                <h4 class="font-bold text-lg text-slate-800 dark:text-white">📖 Buku yang Sedang Saya Baca / Pinjam</h4>
                                <a href="{{ route('catalog.index') }}" class="btn btn-sm btn-ghost text-primary">Cari Buku Baru</a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="table w-full">
                                    <thead>
                                        <tr class="text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700">
                                            <th>Buku</th>
                                            <th>Tanggal Pinjam</th>
                                            <th>Batas Waktu</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($myActiveBorrows as $borrow)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-750 border-b border-slate-100 dark:border-slate-700">
                                                <td class="font-bold">
                                                    {{ $borrow->book->title }}
                                                    @if($borrow->book->isEbook())
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 ml-1.5 whitespace-nowrap align-middle">E-Book</span>
                                                    @endif
                                                </td>
                                                <td>{{ $borrow->borrow_date->format('d M Y') }}</td>
                                                <td>{{ $borrow->due_date->format('d M Y') }}</td>
                                                <td class="whitespace-nowrap">
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"/></svg>
                                                        Sedang Dibaca
                                                    </span>
                                                </td>
                                                <td class="whitespace-nowrap">
                                                    @if($borrow->book->isEbook())
                                                        <div class="flex items-center gap-1.5">
                                                            <a href="{{ route('ebook.read', $borrow->book->id) }}" class="btn btn-xs btn-accent font-semibold text-white">Baca Online</a>
                                                            <form action="{{ route('ebook.finish', $borrow->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-xs btn-success text-white font-semibold shadow-sm" title="Kembalikan / Tandai Selesai Membaca">
                                                                    Selesai
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @else
                                                        <span class="text-xs opacity-60 text-indigo-650 dark:text-indigo-400 font-medium">Pinjaman Fisik (Kembalikan ke Pustakawan)</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-8 opacity-50">
                                                    Anda tidak memiliki peminjaman aktif. Silakan pilih buku dari katalog untuk mulai membaca!
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Riwayat Pembacaan/Peminjaman Buku -->
                        <div class="card bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xl overflow-hidden">
                            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                                <h4 class="font-bold text-lg text-slate-800 dark:text-white">📜 Riwayat Buku yang Selesai Dibaca</h4>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="table w-full">
                                    <thead>
                                        <tr class="text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700">
                                            <th>Buku</th>
                                            <th>Tanggal Pinjam</th>
                                            <th>Tanggal Selesai</th>
                                            <th>Tipe</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($myBorrowHistory as $history)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-750 border-b border-slate-100 dark:border-slate-700">
                                                <td class="font-bold">
                                                    {{ $history->book->title }}
                                                </td>
                                                <td>{{ $history->borrow_date->format('d M Y') }}</td>
                                                <td>{{ $history->return_date ? $history->return_date->format('d M Y') : '—' }}</td>
                                                <td class="whitespace-nowrap">
                                                    @if($history->book->isEbook())
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 whitespace-nowrap">
                                                            E-Book
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-slate-800/40 dark:text-slate-350 whitespace-nowrap">
                                                            Fisik
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                        Selesai Dibaca
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-8 opacity-50">
                                                    Belum ada riwayat pembacaan buku yang diselesaikan.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Rekomendasi Buku / E-Book Terbaru -->
                        <div class="space-y-4">
                            <h4 class="font-bold text-lg text-slate-800 dark:text-white">Koleksi Terpopuler & E-Book Terbaru</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                                @foreach($recommendedBooks as $recBook)
                                    <div class="card bg-white/70 dark:bg-slate-800/70 backdrop-blur-md border border-slate-100 dark:border-slate-700 shadow-lg hover:scale-105 transition-all duration-300 overflow-hidden flex flex-col justify-between">
                                        <figure class="px-3 pt-3 h-48 overflow-hidden bg-slate-100 dark:bg-slate-900 relative">
                                            @if($recBook->cover_image)
                                                <img src="{{ asset('storage/books/covers/' . $recBook->cover_image) }}" alt="{{ $recBook->title }}" class="object-cover h-full rounded-xl w-full">
                                            @else
                                                <div class="flex items-center justify-center h-full text-indigo-500/50 font-bold uppercase select-none w-full">
                                                    {{ substr($recBook->title, 0, 2) }}
                                                </div>
                                            @endif
                                            @if($recBook->isEbook())
                                                <span class="absolute top-4 right-4 badge badge-accent text-white font-bold">E-Book</span>
                                            @endif
                                        </figure>
                                        <div class="p-4 flex-1 flex flex-col justify-between">
                                            <div>
                                                <span class="text-[10px] uppercase font-bold text-indigo-500 tracking-wide">{{ $recBook->category->name }}</span>
                                                <h5 class="font-bold text-slate-800 dark:text-white text-sm line-clamp-1 mt-1">{{ $recBook->title }}</h5>
                                                <p class="text-xs text-slate-400 line-clamp-1 mt-0.5">{{ $recBook->author }}</p>
                                            </div>
                                            <div class="mt-4 flex items-center justify-between">
                                                <div class="rating rating-xs flex items-center">
                                                    <span class="text-[10px] font-bold text-amber-500 mr-1">★ {{ number_format($recBook->averageRating(), 1) }}</span>
                                                </div>
                                                <a href="{{ route('catalog.show', $recBook->id) }}" class="btn btn-xs btn-outline btn-primary text-xs">Detail</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            @endif
            
        </div>
    </div>
</x-app-layout>
