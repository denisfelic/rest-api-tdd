<?php

use App\Http\Controllers\API\BooksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('books.')->group(function () {
    Route::get('/books', [BooksController::class, 'index'])->name('index');
    Route::post('/books', [BooksController::class, 'store'])->name('store');
    Route::get('/books/{book}', [BooksController::class, 'show'])->name('show');
    Route::put('/books/{book}', [BooksController::class, 'update'])->name('update');
    Route::delete('/books/{book}', [BooksController::class, 'delete'])->name('delete');
});
