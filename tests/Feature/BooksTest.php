<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_books_success()
    {
        Book::factory()->count(3)->create();

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Data buku berhasil diambil',
                'data' => []
            ])
            ->assertJsonCount(3, 'data');
    }

    public function test_get_book_by_id_success()
    {
        $book = Book::factory()->create();

        $response = $this->getJson('/api/books/' . $book->id);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Data buku berhasil diambil',
                'data' => [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'year' => $book->year,
                    'stock' => $book->stock,
                ]
            ]);
    }

    public function test_get_book_not_found()
    {
        $response = $this->getJson('/api/books/999');

        $response->assertStatus(404)
            ->assertJson([
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            ]);
    }

    public function test_create_book_success()
    {
        $bookData = [
            'title' => 'Test Book',
            'author' => 'Test Author',
            'year' => 2020,
            'stock' => 10
        ];

        $response = $this->postJson('/api/books', $bookData);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'Buku berhasil ditambahkan',
                'data' => $bookData
            ]);

        $this->assertDatabaseHas('books', $bookData);
    }

    public function test_create_book_validation_error()
    {
        $invalidData = [
            'title' => '',
            'author' => '',
            'year' => 1800,
            'stock' => -1
        ];

        $response = $this->postJson('/api/books', $invalidData);

        $response->assertStatus(400)
            ->assertJson([
                'status' => false,
                'message' => 'Validasi gagal: The title field is required.',
                'data' => null
            ]);
    }

    public function test_update_book_success()
    {
        $book = Book::factory()->create();
        $updatedData = [
            'title' => 'Updated Title',
            'author' => 'Updated Author',
            'year' => 2021,
            'stock' => 20
        ];

        $response = $this->putJson('/api/books/' . $book->id, $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Buku berhasil diperbarui',
                'data' => $updatedData
            ]);

        $this->assertDatabaseHas('books', $updatedData);
    }

    public function test_update_book_not_found()
    {
        $updatedData = [
            'title' => 'Updated Title',
            'author' => 'Updated Author',
            'year' => 2021,
            'stock' => 20
        ];

        $response = $this->putJson('/api/books/999', $updatedData);

        $response->assertStatus(404)
            ->assertJson([
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            ]);
    }

    public function test_delete_book_success()
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson('/api/books/' . $book->id);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Buku berhasil dihapus',
                'data' => null
            ]);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_delete_book_not_found()
    {
        $response = $this->deleteJson('/api/books/999');

        $response->assertStatus(404)
            ->assertJson([
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            ]);
    }
}
