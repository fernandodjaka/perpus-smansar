<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;
use App\Models\Review;
use Carbon\Carbon;

class BookDetail extends Component
{
    public Book $book;
    
    // Review form properties
    public $rating = 5;
    public $reviewText = '';

    // Success/error messages
    public $message = '';
    public $messageType = ''; // 'success' or 'error'

    public function mount(Book $book)
    {
        $this->book = $book;
    }

    public function borrowEbook()
    {
        if (!$this->book->isEbook()) {
            return;
        }

        $student = auth()->user()->student;

        // Check if already borrowed and active
        $existing = Borrow::where('student_id', $student->id)
            ->where('book_id', $this->book->id)
            ->where('status', 'borrowed')
            ->first();

        if (!$existing) {
            Borrow::create([
                'student_id' => $student->id,
                'book_id' => $this->book->id,
                'borrow_date' => Carbon::today(),
                'due_date' => Carbon::today()->addYears(1), // E-Books have virtually unlimited time
                'status' => 'borrowed',
            ]);
        }

        return redirect()->route('ebook.read', $this->book->id);
    }

    public function requestReservation()
    {
        $student = auth()->user()->student;

        // Check if student has reached borrow limit
        if (!$student->canBorrow()) {
            $this->message = 'Batas maksimal peminjaman Anda (' . $student->max_borrow_limit . ' buku) telah tercapai.';
            $this->messageType = 'error';
            return;
        }

        // Check if already has an active borrow or pending reservation for this book
        $hasActiveBorrow = Borrow::where('student_id', $student->id)
            ->where('book_id', $this->book->id)
            ->whereIn('status', ['borrowed', 'late'])
            ->exists();

        if ($hasActiveBorrow) {
            $this->message = 'Anda sedang meminjam buku ini saat ini.';
            $this->messageType = 'error';
            return;
        }

        $hasPendingReservation = Reservation::where('student_id', $student->id)
            ->where('book_id', $this->book->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPendingReservation) {
            $this->message = 'Anda sudah membuat antrean reservasi untuk buku ini.';
            $this->messageType = 'error';
            return;
        }

        Reservation::create([
            'student_id' => $student->id,
            'book_id' => $this->book->id,
            'reservation_date' => Carbon::today(),
            'status' => 'pending',
        ]);

        $this->message = $this->book->stock > 0 
            ? 'Pengajuan peminjaman berhasil dibuat. Silakan temui Pustakawan untuk mengambil buku fisik.' 
            : 'Stok habis. Anda berhasil masuk ke antrean reservasi buku ini.';
        $this->messageType = 'success';
    }

    public function submitReview()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'reviewText' => 'nullable|string|max:1000',
        ]);

        $student = auth()->user()->student;

        // Check if student has already reviewed this book
        $existingReview = Review::where('student_id', $student->id)
            ->where('book_id', $this->book->id)
            ->first();

        if ($existingReview) {
            $this->message = 'Anda sudah memberikan ulasan untuk buku ini.';
            $this->messageType = 'error';
            return;
        }

        Review::create([
            'student_id' => $student->id,
            'book_id' => $this->book->id,
            'rating' => $this->rating,
            'review_text' => $this->reviewText,
        ]);

        $this->reviewText = '';
        $this->rating = 5;
        $this->book->load('reviews'); // refresh rating average

        $this->message = 'Terima kasih atas ulasan Anda!';
        $this->messageType = 'success';
    }

    public function render()
    {
        return view('livewire.book-detail', [
            'reviews' => $this->book->reviews()->with('student.user')->latest()->get(),
        ])->layout('layouts.app');
    }
}
