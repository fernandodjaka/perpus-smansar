<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Student;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;
use Carbon\Carbon;

class AdminCirculation extends Component
{
    // Issue form
    public $searchStudent = '';
    public $searchBook = '';
    public $selectedStudentId = null;
    public $selectedBookId = null;
    
    // Auto-complete suggestion arrays
    public $studentSuggestions = [];
    public $bookSuggestions = [];

    // Success/error messages
    public $message = '';
    public $messageType = ''; // 'success' or 'error'

    // Tab control
    public $activeTab = 'borrow'; // 'borrow', 'return', 'reservations'

    // Return search
    public $searchReturnNisn = '';
    public $activeBorrowsForReturn = [];

    public function updatedSearchStudent()
    {
        if (strlen($this->searchStudent) > 1) {
            $this->studentSuggestions = Student::with('user')
                ->where('nisn', 'like', '%' . $this->searchStudent . '%')
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->searchStudent . '%');
                })
                ->take(5)
                ->get()
                ->map(function($student) {
                    return [
                        'id' => $student->id,
                        'nisn' => $student->nisn,
                        'name' => $student->user->name
                    ];
                })
                ->toArray();
        } else {
            $this->studentSuggestions = [];
        }
    }

    public function updatedSearchBook()
    {
        if (strlen($this->searchBook) > 1) {
            $this->bookSuggestions = Book::whereNull('pdf_file') // Physical books only
                ->where(function($q) {
                    $q->where('title', 'like', '%' . $this->searchBook . '%')
                      ->orWhere('isbn', 'like', '%' . $this->searchBook . '%');
                })
                ->take(5)
                ->get()
                ->map(function($book) {
                    return [
                        'id' => $book->id,
                        'isbn' => $book->isbn,
                        'title' => $book->title
                    ];
                })
                ->toArray();
        } else {
            $this->bookSuggestions = [];
        }
    }

    public function selectStudent($id, $name, $nisn)
    {
        $this->selectedStudentId = $id;
        $this->searchStudent = "$name ($nisn)";
        $this->studentSuggestions = [];
    }

    public function selectBook($id, $title, $isbn)
    {
        $this->selectedBookId = $id;
        $this->searchBook = "$title ($isbn)";
        $this->bookSuggestions = [];
    }

    public function processBorrow()
    {
        if (!$this->selectedStudentId || !$this->selectedBookId) {
            $this->message = 'Silakan pilih siswa dan buku terlebih dahulu.';
            $this->messageType = 'error';
            return;
        }

        $student = Student::find($this->selectedStudentId);
        $book = Book::find($this->selectedBookId);

        if (!$student) {
            $this->message = 'Siswa tidak ditemukan.';
            $this->messageType = 'error';
            return;
        }

        if (!$book) {
            $this->message = 'Buku tidak ditemukan.';
            $this->messageType = 'error';
            return;
        }

        // Validate stock
        if ($book->stock <= 0) {
            $this->message = 'Stok buku ini habis. Silakan daftarkan siswa ke antrean reservasi.';
            $this->messageType = 'error';
            return;
        }

        // Validate limit
        if (!$student->canBorrow()) {
            $this->message = 'Siswa ini telah mencapai batas peminjaman maksimal (' . $student->max_borrow_limit . ' buku).';
            $this->messageType = 'error';
            return;
        }

        // Check if student has already borrowed this book
        $alreadyBorrowed = Borrow::where('student_id', $student->id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['borrowed', 'late'])
            ->exists();

        if ($alreadyBorrowed) {
            $this->message = 'Siswa ini sedang meminjam buku ini.';
            $this->messageType = 'error';
            return;
        }

        // Create borrow
        Borrow::create([
            'student_id' => $student->id,
            'book_id' => $book->id,
            'borrow_date' => Carbon::today(),
            'due_date' => Carbon::today()->addDays(7), // default 7 days policy
            'status' => 'borrowed',
        ]);

        // Decrement stock
        $book->decrement('stock');

        // Reset form
        $this->reset(['selectedStudentId', 'selectedBookId', 'searchStudent', 'searchBook']);
        $this->message = 'Peminjaman berhasil diproses!';
        $this->messageType = 'success';
    }

    public function searchLoansForReturn()
    {
        $student = Student::where('nisn', $this->searchReturnNisn)->first();

        if (!$student) {
            $this->activeBorrowsForReturn = [];
            $this->message = 'NISN siswa tidak ditemukan.';
            $this->messageType = 'error';
            return;
        }

        $this->activeBorrowsForReturn = Borrow::with('book.shelf')
            ->where('student_id', $student->id)
            ->whereIn('status', ['borrowed', 'late'])
            ->get()
            ->map(function($borrow) {
                return [
                    'id'          => $borrow->id,
                    'book_title'  => $borrow->book->title,
                    'book_isbn'   => $borrow->book->isbn,
                    'shelf_name'  => $borrow->book->shelf->name,
                    'borrow_date' => $borrow->borrow_date->format('d/m/Y'),
                    'due_date'    => $borrow->due_date->format('d/m/Y'),
                    'status'      => $borrow->status,
                ];
            })
            ->toArray();

        if (count($this->activeBorrowsForReturn) === 0) {
            $this->message = 'Siswa ini tidak memiliki peminjaman aktif.';
            $this->messageType = 'success';
        } else {
            $this->message = '';
        }
    }

    public function processReturn($borrowId)
    {
        $borrow = Borrow::find($borrowId);

        if (!$borrow) {
            $this->message = 'Transaksi peminjaman tidak ditemukan.';
            $this->messageType = 'error';
            return;
        }

        // Pengembalian gratis — tidak ada denda
        $borrow->update([
            'return_date' => Carbon::today(),
            'fine_amount' => 0,
            'status'      => 'returned',
        ]);

        // Tambah stok kembali
        $borrow->book->increment('stock');

        $this->message = 'Pengembalian berhasil! Buku "' . $borrow->book->title . '" telah dikembalikan.';
        $this->messageType = 'success';

        // Refresh daftar pinjaman aktif
        $this->searchLoansForReturn();
    }

    public function approveReservation($resId)
    {
        $res = Reservation::find($resId);

        if (!$res) {
            $this->message = 'Reservasi tidak ditemukan.';
            $this->messageType = 'error';
            return;
        }

        $student = $res->student;
        $book = $res->book;

        // Check if student has reached limit
        if (!$student->canBorrow()) {
            $this->message = 'Siswa telah mencapai batas peminjaman maksimal.';
            $this->messageType = 'error';
            return;
        }

        // Check stock
        if ($book->stock <= 0) {
            $this->message = 'Stok buku ini masih kosong.';
            $this->messageType = 'error';
            return;
        }

        // Create borrow record
        Borrow::create([
            'student_id' => $student->id,
            'book_id' => $book->id,
            'borrow_date' => Carbon::today(),
            'due_date' => Carbon::today()->addDays(7),
            'status' => 'borrowed',
        ]);

        // Update reservation status
        $res->update(['status' => 'approved']);

        // Decrement stock
        $book->decrement('stock');

        $this->message = 'Reservasi disetujui, buku dipinjamkan!';
        $this->messageType = 'success';
    }

    public function cancelReservation($resId)
    {
        $res = Reservation::find($resId);
        if ($res) {
            $res->update(['status' => 'cancelled']);
            $this->message = 'Reservasi berhasil dibatalkan.';
            $this->messageType = 'success';
        }
    }

    public function render()
    {
        return view('livewire.admin-circulation', [
            'pendingReservations' => Reservation::with(['student.user', 'book'])
                ->where('status', 'pending')
                ->latest()
                ->get(),
        ])->layout('layouts.app');
    }
}
