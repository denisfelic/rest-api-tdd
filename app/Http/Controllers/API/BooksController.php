<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return response()->json($books);
    }

    public function show(Book $book)
    {
        return response(new BookResource($book));
    }

    public function store()
    {
        try {

            $bookData = request()->only('name', 'isbn');
            $book = new Book();
            $book->name = $bookData['name'];
            $book->isbn = $bookData['isbn'];
            $book->save();
            return response(new BookResource($book), 201);
        } catch (\Throwable $th) {
            return response()->status(500);
        }
    }

    public function update(Book $book)
    {
        $book->name = request('name');
        $book->save();

        return response(
            new BookResource($book)
        );
    }

    public function delete(Book $book)
    {
        $book->delete();
        return response('', 204);
    }
}
