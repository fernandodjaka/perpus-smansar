<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Category;
use App\Models\Shelf;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin (Pustakawan)
        User::firstOrCreate(
            ['email' => 'admin@perpus.sch.id'],
            [
                'name' => 'Admin Pustakawan',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // 2. Seed Siswa 1
        $userStudent1 = User::firstOrCreate(
            ['email' => 'budi@perpus.sch.id'],
            [
                'name' => 'Budi Santoso',
                'password' => bcrypt('password'),
                'role' => 'student',
            ]
        );

        Student::firstOrCreate(
            ['user_id' => $userStudent1->id],
            [
                'nisn' => '1234567890',
                'class' => 'XII RPL 1',
                'phone_number' => '081234567890',
                'address' => 'Jl. Anggrek No. 12, Kota Bandung',
                'max_borrow_limit' => 3,
            ]
        );

        // 3. Seed Siswa 2
        $userStudent2 = User::firstOrCreate(
            ['email' => 'siti@perpus.sch.id'],
            [
                'name' => 'Siti Aminah',
                'password' => bcrypt('password'),
                'role' => 'student',
            ]
        );

        Student::firstOrCreate(
            ['user_id' => $userStudent2->id],
            [
                'nisn' => '0987654321',
                'class' => 'XI RPL 2',
                'phone_number' => '082198765432',
                'address' => 'Jl. Melati No. 5, Kota Bandung',
                'max_borrow_limit' => 3,
            ]
        );

        // Seed Siswa Uji 3
        $userStudent3 = User::firstOrCreate(
            ['email' => 'andi@perpus.sch.id'],
            [
                'name' => 'Andi Wijaya',
                'password' => bcrypt('password'),
                'role' => 'student',
            ]
        );

        Student::firstOrCreate(
            ['user_id' => $userStudent3->id],
            [
                'nisn' => '1122334455',
                'class' => 'X RPL 1',
                'phone_number' => '081211223344',
                'address' => 'Jl. Mawar No. 8, Kota Bandung',
                'max_borrow_limit' => 3,
            ]
        );

        // Seed Siswa Uji 4
        $userStudent4 = User::firstOrCreate(
            ['email' => 'rara@perpus.sch.id'],
            [
                'name' => 'Rara Anindita',
                'password' => bcrypt('password'),
                'role' => 'student',
            ]
        );

        Student::firstOrCreate(
            ['user_id' => $userStudent4->id],
            [
                'nisn' => '5544332211',
                'class' => 'XI RPL 1',
                'phone_number' => '082155443322',
                'address' => 'Jl. Tulip No. 3, Kota Bandung',
                'max_borrow_limit' => 3,
            ]
        );

        // Seed Siswa Uji 5
        $userStudent5 = User::firstOrCreate(
            ['email' => 'dewi@perpus.sch.id'],
            [
                'name' => 'Dewi Lestari',
                'password' => bcrypt('password'),
                'role' => 'student',
            ]
        );

        Student::firstOrCreate(
            ['user_id' => $userStudent5->id],
            [
                'nisn' => '9988776655',
                'class' => 'XII RPL 2',
                'phone_number' => '083899887766',
                'address' => 'Jl. Kamboja No. 15, Kota Bandung',
                'max_borrow_limit' => 3,
            ]
        );

        // 4. Seed Categories
        $fiction = Category::firstOrCreate(['name' => 'Fiksi'], ['slug' => 'fiksi']);
        $science = Category::firstOrCreate(['name' => 'Sains'], ['slug' => 'sains']);
        $tech = Category::firstOrCreate(['name' => 'Teknologi'], ['slug' => 'teknologi']);
        $history = Category::firstOrCreate(['name' => 'Sejarah'], ['slug' => 'sejarah']);

        // 5. Seed Shelves
        $shelfA = Shelf::firstOrCreate(['name' => 'Rak A-1'], ['description' => 'Khusus buku fiksi dan novel']);
        $shelfB = Shelf::firstOrCreate(['name' => 'Rak B-1'], ['description' => 'Khusus buku eksakta, sains, dan teknologi']);
        $shelfC = Shelf::firstOrCreate(['name' => 'Rak C-1'], ['description' => 'Khusus buku humaniora, sejarah, dan geografi']);

        // 6. Seed Books (Physical & E-Books)
        Book::firstOrCreate(
            ['isbn' => '9789793062791'],
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'publication_year' => 2005,
                'description' => 'Sebuah novel luar biasa yang menceritakan kehidupan 10 anak di Pulau Belitung dengan keterbatasan fasilitas sekolah namun memiliki mimpi yang sangat tinggi.',
                'category_id' => $fiction->id,
                'shelf_id' => $shelfA->id,
                'cover_image' => 'laskar_pelangi.png',
                'pdf_file' => null,
                'stock' => 5,
                'total_pages' => 529,
            ]
        );

        Book::firstOrCreate(
            ['isbn' => '9786022411918'],
            [
                'title' => 'Fisika Dasar Jilid 1',
                'author' => 'Halliday & Resnick',
                'publisher' => 'Erlangga',
                'publication_year' => 2012,
                'description' => 'Buku teks standar perkuliahan fisika dasar yang mencakup mekanika, termodinamika, dan gelombang dengan pembahasan mendalam dan latihan soal lengkap.',
                'category_id' => $science->id,
                'shelf_id' => $shelfB->id,
                'cover_image' => 'fisika_dasar.png',
                'pdf_file' => null,
                'stock' => 3,
                'total_pages' => 350,
            ]
        );

        Book::firstOrCreate(
            ['isbn' => '9786028755019'],
            [
                'title' => 'Pemrograman Modern dengan Laravel',
                'author' => 'Rian Kristanto',
                'publisher' => 'Informatika',
                'publication_year' => 2024,
                'description' => 'Buku panduan praktis membangun aplikasi web modern menggunakan framework Laravel 11 dengan Livewire, Tailwind CSS, dan arsitektur database MySQL.',
                'category_id' => $tech->id,
                'shelf_id' => $shelfB->id,
                'cover_image' => 'laravel_book.png',
                'pdf_file' => 'books/pdfs/laravel_sample.pdf',
                'stock' => 0,
                'total_pages' => 200,
            ]
        );

        Book::firstOrCreate(
            ['isbn' => '9789791684781'],
            [
                'title' => 'Sejarah Dunia yang Disembunyikan',
                'author' => 'Jonathan Black',
                'publisher' => 'Alvabet',
                'publication_year' => 2015,
                'description' => 'Buku ini membuka tabir rahasia organisasi misterius di dunia dan menceritakan kembali sejarah peradaban dari sudut pandang yang tidak biasa.',
                'category_id' => $history->id,
                'shelf_id' => $shelfC->id,
                'cover_image' => 'sejarah_dunia.png',
                'pdf_file' => 'books/pdfs/history_sample.pdf',
                'stock' => 0,
                'total_pages' => 640,
            ]
        );

        Book::firstOrCreate(
            ['isbn' => '9786020332901'],
            [
                'title' => 'Bumi',
                'author' => 'Tere Liye',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2014,
                'description' => 'Petualangan 3 sahabat (Raib, Seli, Ali) yang menyadari bahwa mereka memiliki kekuatan khusus dan bertualang ke dunia paralel Klan Bulan.',
                'category_id' => $fiction->id,
                'shelf_id' => $shelfA->id,
                'cover_image' => null,
                'pdf_file' => null,
                'stock' => 4,
                'total_pages' => 440,
            ]
        );

        Book::firstOrCreate(
            ['isbn' => '9786020332857'],
            [
                'title' => 'Kosmos',
                'author' => 'Carl Sagan',
                'publisher' => 'Kepustakaan Populer Gramedia',
                'publication_year' => 2016,
                'description' => 'Buku sains populer legendaris tentang asal-usul alam semesta, sejarah perkembangan ilmu pengetahuan, dan masa depan penjelajahan luar aksa manusia.',
                'category_id' => $science->id,
                'shelf_id' => $shelfB->id,
                'cover_image' => null,
                'pdf_file' => null,
                'stock' => 2,
                'total_pages' => 480,
            ]
        );

        Book::firstOrCreate(
            ['isbn' => '9780132350884'],
            [
                'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'author' => 'Robert C. Martin',
                'publisher' => 'Prentice Hall',
                'publication_year' => 2008,
                'description' => 'Buku panduan klasik bagi programmer untuk menulis kode yang bersih, mudah dibaca, serta dipelihara dengan prinsip agile dan refactoring.',
                'category_id' => $tech->id,
                'shelf_id' => $shelfB->id,
                'cover_image' => 'clean_code.png',
                'pdf_file' => 'books/pdfs/clean_code.pdf',
                'stock' => 0,
                'total_pages' => 464,
            ]
        );

        Book::firstOrCreate(
            ['isbn' => '9781451648539'],
            [
                'title' => 'Steve Jobs',
                'author' => 'Walter Isaacson',
                'publisher' => 'Simon & Schuster',
                'publication_year' => 2011,
                'description' => 'Biografi resmi pendiri Apple Inc. Steve Jobs, berdasarkan wawancara mendalam dengannya, keluarganya, kawan, serta kompetitornya.',
                'category_id' => $history->id,
                'shelf_id' => $shelfC->id,
                'cover_image' => 'steve_jobs.png',
                'pdf_file' => 'books/pdfs/steve_jobs.pdf',
                'stock' => 0,
                'total_pages' => 627,
            ]
        );

        Book::firstOrCreate(
            ['isbn' => '9786230030582'],
            [
                'title' => 'Detektif Conan Vol 100',
                'author' => 'Gosho Aoyama',
                'publisher' => 'Elex Media Komputindo',
                'publication_year' => 2022,
                'description' => 'Volume ke-100 dari komik detektif legendaris Conan Edogawa (Shinichi Kudo) yang menyuguhkan kasus-kasus pelik serta konfrontasi dengan Organisasi Hitam.',
                'category_id' => $fiction->id,
                'shelf_id' => $shelfA->id,
                'cover_image' => null,
                'pdf_file' => null,
                'stock' => 10,
                'total_pages' => 180,
            ]
        );
    }
}
