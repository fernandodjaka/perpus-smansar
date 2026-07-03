<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>E-Library - Perpustakaan Online Sekolah</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts & Styling -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Theme Switcher: di <head> untuk mencegah flash -->
        <script>
            (function () {
                var saved = localStorage.getItem('theme') || 'light';
                if (saved !== 'dark' && saved !== 'light') {
                    saved = 'light';
                }
                function applyTheme(t) {
                    var resolved = t === 'dark' ? 'dark' : 'light';
                    if (resolved === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                    document.documentElement.setAttribute('data-theme', resolved);
                }
                applyTheme(saved);
                // Re-apply theme on Livewire page transitions (wire:navigate)
                document.addEventListener('livewire:navigated', function() {
                    var current = localStorage.getItem('theme') || 'light';
                    applyTheme(current);
                });
                window.themeManager = {
                    getTheme: function() { 
                        var t = localStorage.getItem('theme');
                        return (t === 'dark' || t === 'light') ? t : 'light';
                    },
                    setTheme: function(t) {
                        var resolved = t === 'dark' ? 'dark' : 'light';
                        localStorage.setItem('theme', resolved);
                        applyTheme(resolved);
                        window.dispatchEvent(new CustomEvent('theme-changed', { detail: resolved }));
                    }
                };
            })();
        </script>
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50 dark:bg-slate-950 dark:text-slate-100">
        
        <!-- Glassmorphism Navbar -->
        <header class="sticky top-0 z-50 bg-white/70 dark:bg-slate-900/70 backdrop-blur-md border-b border-slate-100 dark:border-slate-800 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-600 rounded-xl text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="font-extrabold text-xl tracking-wider text-slate-850 dark:text-white">E-LIBRARY</span>
                    </div>

                    <!-- Actions -->
                    <nav class="flex items-center gap-3">
                        <!-- Theme Selector -->
                        <div class="relative" x-data="{
                            open: false,
                            currentTheme: window.themeManager ? window.themeManager.getTheme() : 'light',
                            setTheme(t) {
                                this.currentTheme = t;
                                this.open = false;
                                if (window.themeManager) window.themeManager.setTheme(t);
                            }
                        }" x-on:theme-changed.window="currentTheme = $event.detail" @click.outside="open = false">
                            <button @click="open = !open"
                                class="flex items-center justify-center w-8 h-8 rounded-full border border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 transition-colors shadow-sm">
                                <svg x-show="currentTheme === 'light'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                                </svg>
                                <svg x-show="currentTheme === 'dark'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                            </button>

                            <div x-show="open" x-transition
                                class="absolute right-0 top-10 w-32 rounded-lg shadow-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 z-50 overflow-hidden py-1">
                                <button @click="setTheme('light')"
                                    class="w-full flex items-center gap-2 px-3 py-2 text-xs transition-colors"
                                    :class="currentTheme === 'light' ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800'">
                                    <span>Terang</span>
                                    <span x-show="currentTheme === 'light'" class="ml-auto">✓</span>
                                </button>
                                <button @click="setTheme('dark')"
                                    class="w-full flex items-center gap-2 px-3 py-2 text-xs transition-colors"
                                    :class="currentTheme === 'dark' ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800'">
                                    <span>Gelap</span>
                                    <span x-show="currentTheme === 'dark'" class="ml-auto">✓</span>
                                </button>
                            </div>
                        </div>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-sm font-bold text-white shadow-md">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-ghost btn-sm font-semibold">
                                    Masuk
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-indigo btn-sm text-white font-bold shadow-md">
                                        Daftar
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </nav>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative overflow-hidden pt-12 pb-24 md:py-32">
            <!-- Decorative blur blobs -->
            <div class="absolute top-1/4 -left-32 w-80 h-80 rounded-full bg-indigo-500/10 blur-3xl"></div>
            <div class="absolute bottom-10 -right-32 w-96 h-96 rounded-full bg-emerald-500/10 blur-3xl"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <!-- Text Area -->
                <div class="space-y-6 text-center md:text-left">
                    <span class="badge badge-accent text-white font-bold text-xs uppercase tracking-widest px-3 py-1.5 shadow-md">Platform Perpustakaan Digital</span>
                    <h1 class="text-4xl md:text-6xl font-black tracking-tight leading-tight text-slate-850 dark:text-white">
                        Jendela Dunia Baru <br/>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-500 dark:from-indigo-400 dark:to-purple-400">Dalam Genggamanmu</span>
                    </h1>
                    <p class="text-base md:text-lg text-slate-600 dark:text-slate-350 leading-relaxed max-w-xl mx-auto md:mx-0">
                        Akses ribuan koleksi buku fisik sekolah dan baca ratusan E-Book interaktif kapan saja dan di mana saja. Mudahnya literasi masa depan siswa.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-md px-8 text-white font-bold shadow-lg shadow-indigo-500/25">
                            Mulai Membaca E-Book
                        </a>
                        <a href="#koleksi" class="btn btn-outline btn-md px-8">
                            Jelajahi Katalog
                        </a>
                    </div>
                </div>

                <!-- Visual Area -->
                <div class="flex justify-center relative">
                    <div class="relative w-80 h-96 bg-gradient-to-tr from-indigo-500/20 to-violet-500/20 rounded-3xl p-6 border border-white/10 shadow-2xl flex items-center justify-center">
                        <!-- Floating graphic -->
                        <div class="w-56 h-80 bg-slate-900 rounded-2xl shadow-2xl overflow-hidden border border-slate-800 flex flex-col justify-between p-4 relative hover:rotate-2 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-b from-indigo-500/10 to-transparent"></div>
                            <div class="flex justify-between items-center text-[10px] text-indigo-400 font-bold uppercase tracking-widest">
                                <span>Featured</span>
                                <span class="badge badge-accent badge-xs text-[8px] font-extrabold">E-BOOK</span>
                            </div>
                            <div class="space-y-2 mt-8 z-10">
                                <h4 class="text-white font-extrabold text-lg leading-tight">Pemrograman Modern Laravel 12</h4>
                                <p class="text-indigo-200 text-xs font-semibold">Oleh Rian Kristanto</p>
                            </div>
                            <div class="border-t border-slate-800 pt-4 flex justify-between items-center z-10">
                                <span class="text-[10px] text-slate-400">SMA Negeri 1</span>
                                <span class="text-xs text-emerald-400 font-bold">Baca Online</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-12 bg-white dark:bg-slate-900 border-y border-slate-100 dark:border-slate-850">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <h4 class="text-3xl md:text-4xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $totalBooks }}</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 uppercase mt-1 tracking-wider font-bold">Total Koleksi Buku</p>
                    </div>
                    <div>
                        <h4 class="text-3xl md:text-4xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $totalEbooks }}</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 uppercase mt-1 tracking-wider font-bold">E-Book Digital</p>
                    </div>
                    <div>
                        <h4 class="text-3xl md:text-4xl font-extrabold text-amber-500">100%</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 uppercase mt-1 tracking-wider font-bold">Gratis untuk Siswa</p>
                    </div>
                    <div>
                        <h4 class="text-3xl md:text-4xl font-extrabold text-rose-500">24/7</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 uppercase mt-1 tracking-wider font-bold">Akses Baca Online</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Books Section -->
        <section id="koleksi" class="py-24 bg-slate-50 dark:bg-slate-950">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
                <div class="text-center space-y-3">
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-850 dark:text-white">Buku & E-Book Terkini</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-xl mx-auto">
                        Berikut adalah sebagian kecil dari katalog buku yang siap dipinjam atau dibaca secara digital oleh anggota perpustakaan.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                    @foreach($featuredBooks as $book)
                        <div class="card bg-white/70 dark:bg-slate-900/70 backdrop-blur-md border border-slate-100 dark:border-slate-800 shadow-xl hover:scale-105 transition-all duration-300 overflow-hidden flex flex-col justify-between">
                            <figure class="px-3 pt-3 h-52 overflow-hidden bg-slate-100 dark:bg-slate-950 relative">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/books/covers/' . $book->cover_image) }}" alt="{{ $book->title }}" class="object-cover h-full w-full rounded-2xl">
                                @else
                                    <div class="flex items-center justify-center h-full text-indigo-500/40 font-bold uppercase select-none w-full">
                                        {{ substr($book->title, 0, 2) }}
                                    </div>
                                @endif
                                @if($book->isEbook())
                                    <span class="absolute top-5 right-5 badge badge-accent text-white font-bold text-[10px]">E-Book</span>
                                @else
                                    <span class="absolute top-5 right-5 badge badge-indigo text-white font-bold text-[10px]">Buku Fisik</span>
                                @endif
                            </figure>
                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-indigo-500 tracking-wide">{{ $book->category->name }}</span>
                                    <h4 class="font-bold text-slate-850 dark:text-white text-sm line-clamp-1 mt-1">{{ $book->title }}</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $book->author }}</p>
                                </div>
                                <div class="mt-4 flex items-center justify-between border-t border-slate-100 dark:border-slate-800 pt-3">
                                    <span class="text-[10px] font-bold text-amber-500 flex items-center gap-0.5">★ {{ number_format($book->averageRating(), 1) }}</span>
                                    <a href="{{ route('login') }}" class="btn btn-xs btn-outline btn-primary text-xs">Pinjam / Baca</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="text-center pt-6">
                    <a href="{{ route('login') }}" class="btn btn-indigo text-white font-bold px-8 shadow-md">
                        Masuk untuk Jelajahi Seluruh Katalog
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-12 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-850 text-slate-500 text-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
                <div class="flex justify-center items-center gap-2 font-bold text-slate-800 dark:text-white">
                    <div class="p-1 bg-indigo-600 rounded-md text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <span>E-LIBRARY SEKOLAH</span>
                </div>
                <p class="text-xs">&copy; 2026 E-Library Perpustakaan Sekolah. Hak Cipta Dilindungi.</p>
            </div>
        </footer>

    </body>
</html>
