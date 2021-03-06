<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Backend\EditCategoryRequest;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Book;
use Exception;
use DB;
use App\Http\Requests\Backend\CreateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fields = [
            'categories.id',
            'categories.title',
            DB::raw('COUNT(DISTINCT(books.id)) AS total_books'),
        ];
        $categories = Category::select($fields)
            ->leftJoin('books', 'books.category_id', '=', 'categories.id')
            ->groupBy('categories.id')
            ->paginate(config('define.categories.limit_rows'));

        return view('backend.categories.index', ['categories' => $categories]);
    }

    /**
     * Delete a category and return book to category default.
     *
     * @param Category $category object category
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // Can't delete default category
        if ($category->id == Category::CATEGORY_DEFAULT) {
            flash(__('categories.can_not_delete_default_category'))->warning();
            return redirect()->back();
        }
        $title = $category->title;
        try {
            $category->delete();
            flash(__('categories.delete_category_success', ['name' => $title]))->success();
        } catch (Exception $e) {
            \Log::error($e);
            flash(__('categories.delete_category_fail', ['name' => $title]))->error();
        }
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateCategoryRequest $request $request
     *
     * @return void
     */
    public function store(CreateCategoryRequest $request)
    {
        $title = $request->title;
        try {
            Category::create([
                'title' => $title,
            ]);
            flash(__('categories.add_category_success', ['name' => $title]))->success();
        } catch (Exception $e) {
            \Log::error($e);
            flash(__('categories.add_category_fail', ['name' => $title]))->error();
        }
    }
    
    /**
     * Update infomation of Category.
     *
     * @param App\Http\Requests\Backend\EditCategoryRequest $request  category request
     * @param App\Model\Category                            $category object category
     *
     * @return \Illuminate\Http\Response
     */
    public function update(EditCategoryRequest $request, Category $category)
    {
        DB::beginTransaction();
        $result = false;
        try {
            $result = $category->update($request->only('title'));
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
        return response(array('result' => $result));
    }
}
