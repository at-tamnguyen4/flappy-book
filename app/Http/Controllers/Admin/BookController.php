<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\EditBookRequest;
use App\Http\Requests\backend\CreateBookRequest;
use Illuminate\Support\Facades\DB;
use App\Model\Book;
use App\Model\Category;

class BookController extends Controller
{

    /**
     * Display a listing of the books.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fields = [
            'books.id',
            'books.title',
            'books.author',
            'books.rating',
            DB::raw('COUNT(borrows.id) AS total_borrowed'),
        ];
        $books = Book::leftJoin('borrows', 'books.id', '=', 'borrows.book_id')
                     ->select($fields)
                     ->groupBy('books.id')
                     ->orderBy('id', 'desc')
                     ->paginate(config('define.row_count'));
        return view('backend.books.index', compact('books'));
    }

    /**
     * Show form edit.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('backend.books.edit');
    }

    /**
     * Update infomation of Book.
     *
     * @param App\Http\Requests\EditBookRequest $request form edit book
     * @param int                               $id      id of book
     *
     * @return \Illuminate\Http\Response
     */
    public function update(EditBookRequest $request, $id)
    {
        dd($request);
        dd($id);
    }

    /**
     * Show the form for creating a new book.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::select('id', 'title')->get();
        return view('backend.books.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request send request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBookRequest $request)
    {
        $title = $request->title;
        echo $title;
    }
}
