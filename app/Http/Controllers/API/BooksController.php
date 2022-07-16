<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
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

    public function store(StoreBookRequest $bookRequest)
    {
        $bookData = $bookRequest->validated();
        $book = new Book();
        $book->name = $bookData['name'];
        $book->isbn = $bookData['isbn'];
        $book->save();
        return response(new BookResource($book), 201);
    }

    public function update(Book $book, UpdateBookRequest $request)
    {
        $requestData = $request->validated();

        if ($requestData['name']){
            $book->name = $requestData['name'];
        }

        if ($request['isbn']){
            $book->isbn = $requestData['isbn'];
        }
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
