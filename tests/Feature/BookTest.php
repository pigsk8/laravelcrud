<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;

class BookTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function canGetAllBooks()
    {
        $books = Book::factory(4)->create();
        $this->getJson(route('books.index'))
            ->assertJsonFragment([
                'title' => $books[0]->title
            ])->assertJsonFragment([
                'title' => $books[1]->title
            ]);
    }

    /** @test */
    public function canGetOneBook()
    {
        $book = Book::factory()->create();
        $this->getJson(route('books.show', $book->id))
            ->assertJsonFragment([
                'title' => $book->title
            ]);
    }

    /** @test */
    public function canStoreBook()
    {

        $this
            ->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor('title');

        $this
            ->postJson(route('books.store'), ['title' => 'book1'])
            ->assertJsonFragment([
                'title' => 'book1'
            ]);

        $this->assertDatabaseHas('books', ['title' => 'book1']);
    }

    /** @test */
    public function canUpdateBook()
    {
        $book = Book::factory()->create();

        $this
            ->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor('title');

        $this
            ->patchJson(route('books.update', $book->id), ['title' => 'book_update'])
            ->assertJsonFragment([
                'title' => 'book_update'
            ]);

        $book2 = Book::factory()->create();

        $this
            ->patchJson(route('books.update', $book2->id), ['title' => 'book_update'])
            ->assertJsonValidationErrorFor('title');

        $this->assertDatabaseHas('books', ['title' => 'book_update']);
    }

    /** @test */
    public function canDeleteBook()
    {
        $book = Book::factory()->create();
        $this->delete(route('books.destroy', $book->id))->assertStatus(204);

        $this->assertDatabaseCount('books', 0);
    }
}
