<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;
use App\Models\Category;
use Livewire\WithPagination;

class BookCatalog extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $type = ''; // 'ebook', 'physical' or ''

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'type' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $booksQuery = Book::with(['category', 'reviews'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('author', 'like', '%' . $this->search . '%')
                      ->orWhere('isbn', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->category, function ($query) {
                $query->where('category_id', $this->category);
            })
            ->when($this->type, function ($query) {
                if ($this->type === 'ebook') {
                    $query->whereNotNull('pdf_file');
                } elseif ($this->type === 'physical') {
                    $query->whereNull('pdf_file');
                }
            });

        return view('livewire.book-catalog', [
            'books' => $booksQuery->paginate(8),
            'categories' => Category::all(),
        ])->layout('layouts.app');
    }
}
