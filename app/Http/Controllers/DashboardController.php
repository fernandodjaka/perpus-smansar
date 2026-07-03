<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;
use App\Models\Student;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $data = [];

        if ($user->isAdmin()) {
            $data['totalBooks'] = Book::count();
            $data['activeBorrows'] = Borrow::whereIn('status', ['borrowed', 'late'])->count();
            $data['activeReservations'] = Reservation::where('status', 'pending')->count();
            $data['totalStudents'] = Student::count();
            $data['recentBorrows'] = Borrow::with(['student.user', 'book'])->latest()->take(8)->get();
        } else {
            $student = $user->student;
            // Create profile dynamically if missing (e.g. if registering without seeder)
            if (!$student) {
                $student = Student::create([
                    'user_id' => $user->id,
                    'nisn' => 'NISN' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                    'class' => 'Umum',
                ]);
                $user->load('student');
            }
            $data['student'] = $student;
            $data['activeBorrowsCount'] = Borrow::where('student_id', $student->id)->whereIn('status', ['borrowed', 'late'])->count();
            $data['activeReservationsCount'] = Reservation::where('student_id', $student->id)->where('status', 'pending')->count();
            $data['myActiveBorrows'] = Borrow::with('book')->where('student_id', $student->id)->whereIn('status', ['borrowed', 'late'])->latest()->get();
            $data['myBorrowHistory'] = Borrow::with('book')->where('student_id', $student->id)->where('status', 'returned')->latest()->take(10)->get();
            $data['recommendedBooks'] = Book::with('category')->latest()->take(4)->get();
        }

        return view('dashboard', $data);
    }

    public function finishEbook(Borrow $borrow)
    {
        $user = auth()->user();
        
        // Memastikan ini e-book dan dipinjam oleh siswa yang bersangkutan
        if (!$user->isAdmin() && $borrow->student_id === $user->student?->id && $borrow->book->isEbook()) {
            $borrow->update([
                'status' => 'returned',
                'return_date' => \Carbon\Carbon::today(),
            ]);
            return redirect()->route('dashboard')->with('success', 'E-Book berhasil dikembalikan/ditandai selesai.');
        }

        return redirect()->route('dashboard')->with('error', 'Aksi tidak valid.');
    }
}
