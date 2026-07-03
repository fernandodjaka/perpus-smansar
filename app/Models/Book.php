<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'isbn',
        'author',
        'publisher',
        'publication_year',
        'description',
        'category_id',
        'shelf_id',
        'cover_image',
        'pdf_file',
        'stock',
        'total_pages',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
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

    public function isEbook(): bool
    {
        return !empty($this->pdf_file);
    }

    public function isAvailable(): bool
    {
        return $this->isEbook() || $this->stock > 0;
    }

    public function averageRating(): float
    {
        return (float) $this->reviews()->avg('rating') ?: 0.0;
    }
}
