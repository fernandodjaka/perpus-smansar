<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nisn',
        'class',
        'phone_number',
        'address',
        'max_borrow_limit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function activeBorrowsCount(): int
    {
        return $this->borrows()->whereIn('status', ['borrowed', 'late'])->count();
    }

    public function canBorrow(): bool
    {
        return true;
    }
}
