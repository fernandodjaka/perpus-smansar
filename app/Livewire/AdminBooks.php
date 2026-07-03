<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Book;
use App\Models\Category;
use App\Models\Shelf;

class AdminBooks extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '';
    public $activeForm = false; // Add/Edit form active or list view
    public $isEditMode = false;
    public $bookId = null;

    // Form fields
    public $title = '';
    public $isbn = '';
    public $author = '';
    public $publisher = '';
    public $publication_year = '';
    public $description = '';
    public $category_id = '';
    public $shelf_id = '';
    public $stock = 0;
    public $total_pages = 0;
    public $coverImageFile = null;
    public $pdfFileFile = null;

    // Success/error messages
    public $message = '';
    public $messageType = ''; // 'success' or 'error'

    protected $rules = [
        'title' => 'required|string|max:255',
        'isbn' => 'required|string|max:50',
        'author' => 'required|string|max:255',
        'publisher' => 'required|string|max:255',
        'publication_year' => 'required|integer|min:1000|max:2100',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'shelf_id' => 'required|exists:shelves,id',
        'stock' => 'required|integer|min:0',
        'total_pages' => 'required|integer|min:1',
        'coverImageFile' => 'nullable|image|max:2048', // 2MB Max
        'pdfFileFile' => 'nullable|mimes:pdf|max:10240', // 10MB Max
    ];

    public function showCreateForm()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->activeForm = true;
    }

    public function showEditForm($id)
    {
        $this->resetForm();
        $book = Book::find($id);
        if (!$book) {
            $this->message = 'Buku tidak ditemukan.';
            $this->messageType = 'error';
            return;
        }

        $this->bookId = $book->id;
        $this->title = $book->title;
        $this->isbn = $book->isbn;
        $this->author = $book->author;
        $this->publisher = $book->publisher;
        $this->publication_year = $book->publication_year;
        $this->description = $book->description;
        $this->category_id = $book->category_id;
        $this->shelf_id = $book->shelf_id;
        $this->stock = $book->stock;
        $this->total_pages = $book->total_pages;

        $this->isEditMode = true;
        $this->activeForm = true;
    }

    public function saveBook()
    {
        $validationRules = $this->rules;
        $this->validate($validationRules);

        $coverName = null;
        if ($this->coverImageFile) {
            // Save to public storage books/covers
            $coverName = $this->coverImageFile->hashName();
            $this->coverImageFile->storeAs('books/covers', $coverName, 'public');
        }

        $pdfName = null;
        if ($this->pdfFileFile) {
            // Save to public storage books/pdfs
            $pdfName = $this->pdfFileFile->hashName();
            $this->pdfFileFile->storeAs('books/pdfs', $pdfName, 'public');
        }

        $data = [
            'title' => $this->title,
            'isbn' => $this->isbn,
            'author' => $this->author,
            'publisher' => $this->publisher,
            'publication_year' => $this->publication_year,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'shelf_id' => $this->shelf_id,
            'stock' => $this->stock,
            'total_pages' => $this->total_pages,
        ];

        if ($coverName) {
            $data['cover_image'] = $coverName;
        }
        if ($pdfName) {
            $data['pdf_file'] = 'books/pdfs/' . $pdfName;
        }

        if ($this->isEditMode) {
            $book = Book::find($this->bookId);
            $book->update($data);
            $this->message = 'Buku berhasil diperbarui!';
        } else {
            Book::create($data);
            $this->message = 'Buku baru berhasil ditambahkan!';
        }

        $this->messageType = 'success';
        $this->activeForm = false;
        $this->resetForm();
    }

    public function deleteBook($id)
    {
        $book = Book::find($id);
        if ($book) {
            $book->delete();
            $this->message = 'Buku berhasil dihapus.';
            $this->messageType = 'success';
        }
    }

    public function cancel()
    {
        $this->activeForm = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'bookId', 'title', 'isbn', 'author', 'publisher', 'publication_year', 
            'description', 'category_id', 'shelf_id', 'stock', 'total_pages', 
            'coverImageFile', 'pdfFileFile'
        ]);
        $this->resetErrorBag();
    }

    public function render()
    {
        $books = Book::with(['category', 'shelf'])
            ->where('title', 'like', '%' . $this->search . '%')
            ->orWhere('author', 'like', '%' . $this->search . '%')
            ->orWhere('isbn', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin-books', [
            'books' => $books,
            'categories' => Category::all(),
            'shelves' => Shelf::all(),
        ])->layout('layouts.app');
    }
}
