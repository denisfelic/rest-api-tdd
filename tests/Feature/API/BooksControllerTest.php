<?php

namespace Tests\Feature\API;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class BooksControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_books_index_endpoint()
    {
        $books = Book::factory(3)->create();

        $response = $this->getJson(route('books.index'));
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

    public function test_books_create_endpoint()
    {
        $bookRequestData = [
            'name' => 'Book1',
            'isbn' => '123456',
        ];
        $response = $this->postJson(route('books.store'), $bookRequestData);
        $books = Book::all();

        $response->assertStatus(201);
        $response->assertJsonCount(3);
        $response->assertJson(function (AssertableJson $json) use ($books) {
            $json->whereAll([
                'id' => $books->first()->id,
                'name' => $books->first()->name,
                'isbn' => $books->first()->isbn,
            ]);
        });
    }

    public function test_books_show_endpoint()
    {
        // create a book
        Book::factory(5)->create();
        $book = Book::find(2);

        // access end point of book by id
        $response = $this->getJson(route('books.show', $book->id));

        // check if the returned book is equals the book passed by id
        $response->assertStatus(200);
        $response->assertJsonCount(3);

        $response->assertJson(function (AssertableJson $json)  use ($book) {
            $json->whereAll([
                'id' => $book->id,
                'name' => $book->name,
                'isbn' => $book->isbn,
            ]);
        });
    }

    public function test_books_update_endpoint()
    {
        // create new Book
        Book::factory(3)->create();
        $book = Book::find(2);
        $book->name = 'Teste1';
        $book->save();
        assertEquals('Teste1', $book->name);

        // request update to created book
        $newBookName = 'Teste2';
        $request = $this->putJson(route('books.update', $book->id), [
            'name' => $newBookName,
        ]);

        // check if the data of requested book changed
        $request->assertJsonCount(3);
        $request->assertStatus(200);
        $request->assertJson(function (AssertableJson $json) use ($book, $newBookName) {
            $json->whereAll(
                [
                    'id' => $book->id,
                    'name' => $newBookName,
                    'isbn' => $book->isbn,
                ]
            );
        });
    }


    public function test_books_delete_endpoint()
    {
        // create a book
        Book::factory(5)->create();
        $book = Book::find(2);

        assertEquals(count(Book::all()), 5);

        // request for delete book endpoint
        $request = $this->deleteJson(route('books.delete', $book->id));

        // check if the book exists
        assertEquals(count(Book::all()), 4);
        $request->assertStatus(204);

        $request = $this->getJson(route('books.show', $book->id));
        $request->assertStatus(404);

    }
}
