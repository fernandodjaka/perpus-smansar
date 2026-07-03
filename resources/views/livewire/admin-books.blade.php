<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Kelola Koleksi Buku') }}
            </h2>
            @if(!$activeForm)
                <button wire:click="showCreateForm" class="btn btn-primary btn-sm font-semibold shadow-md shadow-indigo-500/25">
                    + Tambah Buku Baru
                </button>
            @endif
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success/Error Alert -->
            @if($message)
                <div class="alert {{ $messageType === 'success' ? 'alert-success text-white' : 'alert-error text-white' }} shadow-lg">
                    <div>
                        <span class="font-bold">{{ $message }}</span>
                    </div>
                    <button class="btn btn-xs btn-circle btn-ghost" wire:click="$set('message', '')">✕</button>
                </div>
            @endif

            <!-- Form: Add/Edit Book -->
            @if($activeForm)
                <div class="card bg-white/70 dark:bg-slate-800/70 backdrop-blur-md border border-slate-100 dark:border-slate-700 shadow-xl">
                    <div class="card-body p-8 space-y-6">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-3">
                            {{ $isEditMode ? 'Edit Informasi Buku' : 'Tambah Buku Baru ke Koleksi' }}
                        </h3>

                        <form wire:submit.prevent="saveBook" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Title -->
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-semibold text-slate-600 dark:text-slate-350">Judul Buku</span></label>
                                    <input wire:model="title" type="text" class="input input-bordered bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white" placeholder="Contoh: Laskar Pelangi" />
                                    @error('title') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- ISBN -->
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-semibold text-slate-600 dark:text-slate-350">ISBN / Barcode</span></label>
                                    <input wire:model="isbn" type="text" class="input input-bordered bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white" placeholder="Contoh: 9789793062791" />
                                    @error('isbn') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- Author -->
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-semibold text-slate-600 dark:text-slate-350">Penulis</span></label>
                                    <input wire:model="author" type="text" class="input input-bordered bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white" placeholder="Contoh: Andrea Hirata" />
                                    @error('author') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- Publisher -->
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-semibold text-slate-600 dark:text-slate-350">Penerbit</span></label>
                                    <input wire:model="publisher" type="text" class="input input-bordered bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white" placeholder="Contoh: Bentang Pustaka" />
                                    @error('publisher') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- Publication Year -->
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-semibold text-slate-600 dark:text-slate-350">Tahun Terbit</span></label>
                                    <input wire:model="publication_year" type="number" class="input input-bordered bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white" placeholder="Contoh: 2005" />
                                    @error('publication_year') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- Pages Count -->
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-semibold text-slate-600 dark:text-slate-350">Jumlah Halaman</span></label>
                                    <input wire:model="total_pages" type="number" class="input input-bordered bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white" placeholder="Contoh: 529" />
                                    @error('total_pages') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- Category Selection -->
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-semibold text-slate-600 dark:text-slate-350">Kategori</span></label>
                                    <select wire:model="category_id" class="select select-bordered bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white">
                                        <option value="">Pilih Kategori...</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- Shelf Selection -->
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-semibold text-slate-600 dark:text-slate-350">Lokasi Rak Fisik</span></label>
                                    <select wire:model="shelf_id" class="select select-bordered bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white">
                                        <option value="">Pilih Rak...</option>
                                        @foreach($shelves as $sh)
                                            <option value="{{ $sh->id }}">{{ $sh->name }} ({{ $sh->description }})</option>
                                        @endforeach
                                    </select>
                                    @error('shelf_id') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- Physical Stock -->
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-semibold text-slate-600 dark:text-slate-350">Stok Fisik (Set ke 0 jika E-Book murni)</span></label>
                                    <input wire:model="stock" type="number" class="input input-bordered bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white" />
                                    @error('stock') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- Cover Image Upload -->
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-semibold text-slate-600 dark:text-slate-350">Sampul Buku (JPG/PNG, Maks 2MB)</span></label>
                                    <input wire:model="coverImageFile" type="file" class="file-input file-input-bordered bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white w-full" />
                                    @error('coverImageFile') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- Ebook PDF Upload -->
                                <div class="form-control md:col-span-2">
                                    <label class="label"><span class="label-text font-semibold text-slate-655 dark:text-slate-350">File E-Book PDF (Unggah jika merupakan buku digital, Maks 10MB)</span></label>
                                    <input wire:model="pdfFileFile" type="file" class="file-input file-input-bordered bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-850 dark:text-white w-full" />
                                    @error('pdfFileFile') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- Description -->
                                <div class="form-control md:col-span-2">
                                    <label class="label"><span class="label-text font-semibold text-slate-600 dark:text-slate-350">Sinopsis / Ringkasan Buku</span></label>
                                    <textarea wire:model="description" class="textarea textarea-bordered h-28 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white" placeholder="Masukkan sinopsis buku..."></textarea>
                                    @error('description') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 border-t border-slate-100 dark:border-slate-700/60 pt-4">
                                <button type="button" wire:click="cancel" class="btn btn-outline btn-neutral">Batal</button>
                                <button type="submit" class="btn btn-primary text-white font-semibold shadow-md px-8">Simpan Informasi Buku</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- List of Books -->
            @if(!$activeForm)
                <div class="card bg-white/70 dark:bg-slate-800/70 backdrop-blur-md border border-slate-100 dark:border-slate-700 shadow-xl overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white">Daftar Koleksi Buku</h3>
                        
                        <div class="relative w-full md:w-80">
                            <label class="input input-bordered input-sm flex items-center gap-2 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari judul, penulis, atau ISBN..." class="grow border-none focus:outline-none focus:ring-0 p-0 text-xs text-slate-800 dark:text-white bg-transparent" />
                            </label>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-slate-500 border-b border-slate-100 dark:border-slate-700">
                                    <th>Sampul</th>
                                    <th>Buku / ISBN</th>
                                    <th>Kategori / Rak</th>
                                    <th>Tipe & Stok</th>
                                    <th>Halaman</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($books as $b)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-200 border-b border-slate-100 dark:border-slate-700">
                                        <td>
                                            <div class="w-12 h-16 bg-slate-100 dark:bg-slate-900 rounded-lg overflow-hidden border border-slate-200/60 shadow-md">
                                                @if($b->cover_image)
                                                    <img src="{{ asset('storage/books/covers/' . $b->cover_image) }}" alt="Cover" class="object-cover w-full h-full">
                                                @else
                                                    <div class="flex items-center justify-center h-full text-indigo-500/40 text-[10px] font-bold">L-E</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="font-bold text-slate-800 dark:text-white">{{ $b->title }}</div>
                                            <div class="text-xs text-slate-400">Penulis: {{ $b->author }} | ISBN: {{ $b->isbn }}</div>
                                        </td>
                                        <td>
                                            <span class="badge badge-indigo badge-outline text-[10px] uppercase font-bold">{{ $b->category->name }}</span>
                                            <div class="text-xs text-slate-400 mt-1">Rak: {{ $b->shelf->name }}</div>
                                        </td>
                                        <td>
                                            @if($b->isEbook())
                                                <span class="badge badge-accent text-white font-bold text-[10px]">E-Book</span>
                                            @else
                                                <span class="badge badge-outline text-[10px] font-bold">Fisik</span>
                                            @endif
                                            <div class="text-xs text-slate-400 mt-1">Stok: {{ $b->stock }} eks</div>
                                        </td>
                                        <td>{{ $b->total_pages }} hlm</td>
                                        <td class="space-x-1">
                                            <button wire:click="showEditForm({{ $b->id }})" class="btn btn-xs btn-outline btn-neutral px-2">
                                                Edit
                                            </button>
                                            <button onclick="confirm('Apakah Anda yakin ingin menghapus buku ini?') || event.stopImmediatePropagation()" wire:click="deleteBook({{ $b->id }})" class="btn btn-xs btn-error text-white px-2">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-6 text-slate-400">Belum ada koleksi buku yang terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center p-4 border-t border-slate-100 dark:border-slate-700">
                        {{ $books->links() }}
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
