<?php

namespace Tests\Feature\API;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BooksControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_books_get_endpoint()
    {
        $books = Book::factory(3)->create();

        $response = $this->getJson('/api/books');
        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $response->assertJson(function (AssertableJson $json) use ($books) {
            $json->whereAllType([
                '0.id' => 'integer',
                '0.isbn' => 'string',
                '0.name' => 'string',
            ]);

            $book = $books->first();
            $json->whereAll([
                '0.id' => $book->id,
                '0.name' => $book->name,
                '0.isbn' => $book->isbn,

            ]);
        });
    }
}
