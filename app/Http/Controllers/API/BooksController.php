<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return response()->json($books);
    }
}
