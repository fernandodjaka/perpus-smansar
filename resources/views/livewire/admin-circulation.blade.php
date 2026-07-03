<div>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Sirkulasi Pustakawan') }}
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Success/Error Alert -->
            @if($message)
                <div class="alert {{ $messageType === 'success' ? 'alert-success text-white' : 'alert-error text-white' }} shadow-lg">
                    <div>
                        <span class="font-bold">{{ $message }}</span>
                    </div>
                    <button class="btn btn-xs btn-circle btn-ghost" wire:click="$set('message', '')">✕</button>
                </div>
            @endif

            <!-- Navigation Tabs Custom -->
            <div class="flex gap-2 p-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-md">
                <button wire:click="$set('activeTab', 'borrow')" 
                    class="flex-1 py-2 px-3 rounded-lg text-sm font-bold transition-all duration-150 flex items-center justify-center gap-1.5 {{ $activeTab === 'borrow' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-350 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                    <span>📚 Peminjaman Baru</span>
                </button>
                <button wire:click="$set('activeTab', 'return')" 
                    class="flex-1 py-2 px-3 rounded-lg text-sm font-bold transition-all duration-150 flex items-center justify-center gap-1.5 {{ $activeTab === 'return' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-350 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                    <span>🔄 Pengembalian Buku</span>
                </button>
                <button wire:click="$set('activeTab', 'reservations')" 
                    class="flex-1 py-2 px-3 rounded-lg text-sm font-bold transition-all duration-150 flex items-center justify-center gap-1.5 {{ $activeTab === 'reservations' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-350 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                    <span>📋 Antrean Reservasi</span>
                    @if($pendingReservations->count() > 0)
                        <span class="badge badge-sm badge-error text-white font-black">{{ $pendingReservations->count() }}</span>
                    @endif
                </button>
            </div>

            <!-- Tab 1: Peminjaman Baru -->
            @if($activeTab === 'borrow')
                <div class="card bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xl">
                    <div class="card-body p-8 space-y-6">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-3">
                            📋 Formulir Sirkulasi Peminjaman
                        </h3>

                        <form wire:submit.prevent="processBorrow" class="space-y-6">
                            <!-- Student Search -->
                            <div class="form-control relative">
                                <label class="label">
                                    <span class="label-text font-semibold">Cari Siswa (Ketik Nama / NISN)</span>
                                </label>
                                <input wire:model.live.debounce.250ms="searchStudent" type="text"
                                    class="input input-bordered w-full"
                                    placeholder="Ketik minimal 2 karakter..." autocomplete="off" />

                                @if(count($studentSuggestions) > 0)
                                    <ul class="absolute z-20 w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl mt-2 top-20 overflow-hidden divide-y divide-slate-100 dark:divide-slate-700">
                                        @foreach($studentSuggestions as $s)
                                            <li>
                                                <button type="button"
                                                    wire:click="selectStudent({{ $s['id'] }}, '{{ addslashes($s['name']) }}', '{{ $s['nisn'] }}')"
                                                    class="w-full text-left px-4 py-3 hover:bg-indigo-50 dark:hover:bg-slate-800 text-sm flex justify-between items-center">
                                                    <span class="font-semibold">{{ $s['name'] }}</span>
                                                    <span class="text-xs opacity-60">NISN: {{ $s['nisn'] }}</span>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <!-- Book Search -->
                            <div class="form-control relative">
                                <label class="label">
                                    <span class="label-text font-semibold">Cari Buku Fisik (Ketik Judul / ISBN)</span>
                                </label>
                                <input wire:model.live.debounce.250ms="searchBook" type="text"
                                    class="input input-bordered w-full"
                                    placeholder="Ketik minimal 2 karakter..." autocomplete="off" />

                                @if(count($bookSuggestions) > 0)
                                    <ul class="absolute z-20 w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl mt-2 top-20 overflow-hidden divide-y divide-slate-100 dark:divide-slate-700">
                                        @foreach($bookSuggestions as $b)
                                            <li>
                                                <button type="button"
                                                    wire:click="selectBook({{ $b['id'] }}, '{{ addslashes($b['title']) }}', '{{ $b['isbn'] }}')"
                                                    class="w-full text-left px-4 py-3 hover:bg-indigo-50 dark:hover:bg-slate-800 text-sm flex justify-between items-center">
                                                    <span class="font-semibold">{{ $b['title'] }}</span>
                                                    <span class="text-xs opacity-60">ISBN: {{ $b['isbn'] }}</span>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <!-- Info Kebijakan (tanpa denda) -->
                            <div class="bg-emerald-50 dark:bg-emerald-950/30 p-4 rounded-2xl text-xs text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900/40">
                                <ul class="list-disc pl-4 space-y-1">
                                    <li>Durasi peminjaman default adalah <strong>14 hari</strong>.</li>
                                    <li>Layanan perpustakaan ini <strong>gratis</strong> — tidak ada denda keterlambatan.</li>
                                    <li>Siswa dapat meminjam buku tanpa batasan jumlah (<strong>unlimited</strong>).</li>
                                </ul>
                            </div>

                            <button type="submit" class="btn btn-primary w-full shadow-lg">
                                ✅ Proses Konfirmasi Peminjaman
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Tab 2: Pengembalian Buku -->
            @if($activeTab === 'return')
                <div class="card bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xl">
                    <div class="card-body p-8 space-y-6">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-3">
                            🔄 Formulir Pengembalian Buku
                        </h3>
                        <p class="text-sm opacity-60">Masukkan NISN siswa untuk mencari daftar pinjaman aktif mereka.</p>

                        <div class="flex gap-2">
                            <input wire:model="searchReturnNisn" type="text"
                                placeholder="Masukkan NISN Siswa..."
                                class="input input-bordered w-full" />
                            <button wire:click="searchLoansForReturn" class="btn btn-primary font-bold px-6">
                                🔍 Cari
                            </button>
                        </div>

                        <!-- Daftar Pinjaman Aktif -->
                        @if(count($activeBorrowsForReturn) > 0)
                            <div class="space-y-3 pt-2">
                                <h4 class="font-bold text-sm opacity-60">Daftar Pinjaman Aktif</h4>
                                @foreach($activeBorrowsForReturn as $item)
                                    <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold truncate">{{ $item['book_title'] }}</p>
                                            <p class="text-xs opacity-50 mt-0.5">Rak: {{ $item['shelf_name'] }} &bull; Pinjam: {{ $item['borrow_date'] }} &bull; Jatuh Tempo: {{ $item['due_date'] }}</p>
                                        </div>
                                        <button wire:click="processReturn({{ $item['id'] }})"
                                            class="btn btn-sm btn-success text-white font-bold ml-4 shrink-0">
                                            ✓ Terima Kembali
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Tab 3: Antrean Reservasi -->
            @if($activeTab === 'reservations')
                <div class="card bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xl overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white">📋 Daftar Antrean Reservasi</h3>
                        <p class="text-xs opacity-50 mt-1">Siswa memesan buku fisik ini saat stok sedang kosong.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-xs opacity-60 border-b border-slate-200 dark:border-slate-700">
                                    <th>Siswa</th>
                                    <th>Buku</th>
                                    <th>Tgl Pengajuan</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingReservations as $res)
                                    <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-750">
                                        <td>
                                            <div class="font-bold">{{ $res->student->user->name }}</div>
                                            <div class="text-xs opacity-50">NISN: {{ $res->student->nisn }}</div>
                                        </td>
                                        <td class="font-semibold">{{ $res->book->title }}</td>
                                        <td>{{ $res->reservation_date->format('d M Y') }}</td>
                                        <td class="font-bold">
                                            @if($res->book->stock > 0)
                                                <span class="text-emerald-600">{{ $res->book->stock }} eks</span>
                                            @else
                                                <span class="text-rose-500">Kosong</span>
                                            @endif
                                        </td>
                                        <td class="space-x-1.5 whitespace-nowrap">
                                            <button wire:click="approveReservation({{ $res->id }})"
                                                class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                                {{ $res->book->stock <= 0 ? 'disabled' : '' }}>
                                                Setujui
                                            </button>
                                            <button wire:click="cancelReservation({{ $res->id }})"
                                                class="px-3 py-1.5 rounded-lg text-xs font-bold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/30 hover:bg-rose-100 dark:hover:bg-rose-900/50 transition-colors">
                                                Tolak
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-8 opacity-40">
                                            Tidak ada pengajuan reservasi pending saat ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
