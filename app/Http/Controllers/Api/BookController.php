<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Model\Book;

class BookController extends ApiController
{
    /**
     * Get list of books
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fields = [
            'id',
            'title',
            'picture',
            'total_rating',
            'rating',
        ];
        $books = Book::select($fields)
            ->orderBy('created_at', 'DESC')
            ->paginate(config('define.book.limit_item'));
            return $this->responseObject($books);
    }
    /**
     * API get detail book
     *
     * @param int $id id of book
     *
     * @return void
     */
    public function show($id)
    {
        $fields = [
            'books.id',
            'books.title',
            'books.category_id',
            'books.description',
            'books.language',
            'books.rating',
            'books.total_rating',
            'books.picture',
            'books.author',
            'books.price','books.unit',
            'books.year',
            'books.page_number',
            'borrows.status',
            'users.id AS user_id',
            'users.name AS donator',
        ];

        $book = Book::select($fields)
                    ->with(['category' => function ($query) {
                        $query->select('id', 'title');
                    }])
                    ->leftJoin('borrows', 'books.id', '=', 'borrows.book_id')
                    ->join('users', 'books.from_person', '=', 'users.employ_code')
                    ->orderBy('borrows.created_at', 'DESC')
                    ->findOrFail($id);

        return $this->responseObject($book);
    }
}
