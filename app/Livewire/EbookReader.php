<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;
use App\Models\Borrow;

class EbookReader extends Component
{
    public Book $book;
    public $borrowId;

    public function mount(Book $book)
    {
        $this->book = $book;

        // Check if book has Ebook file
        if (!$this->book->isEbook()) {
            abort(404, 'Buku ini tidak tersedia dalam format digital.');
        }

        // Authorize: Admin can read, student must have active borrow
        if (!auth()->user()->isAdmin()) {
            $student = auth()->user()->student;
            $activeBorrow = Borrow::where('student_id', $student->id)
                ->where('book_id', $this->book->id)
                ->where('status', 'borrowed')
                ->first();

            if (!$activeBorrow) {
                abort(403, 'Akses ditolak. Anda belum meminjam e-book ini.');
            }
            
            $this->borrowId = $activeBorrow->id;
        }
    }

    public function finishReading()
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard');
        }

        $student = auth()->user()->student;
        $borrow = Borrow::where('student_id', $student->id)
            ->where('book_id', $this->book->id)
            ->where('status', 'borrowed')
            ->first();

        if ($borrow) {
            $borrow->update([
                'return_date' => \Carbon\Carbon::today(),
                'status' => 'returned',
            ]);
        }

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.ebook-reader')->layout('layouts.app');
    }
}
