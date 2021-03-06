<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Model\Book;
use App\Model\User;
use App\Model\Borrow;
use App\Model\Category;
use App\Model\Qrcode;
use Faker\Factory as Faker;
use DB;

class AdminSortBooksTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Override function setUp() for make user login
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /*
     * A Dusk test for click button.
     *
     * @return void
     */
    public function testClickButtonsSort()
    {
        $btnSortNames = ['title', 'author', 'rating', 'total_borrowed'];

        $this->browse(function (Browser $browser) use ($btnSortNames) {

            $browser->loginAs($this->user)
                    ->visit('admin/books');

            foreach ($btnSortNames as $name) {
                $browser->press("#btn-sort-$name a")
                        ->assertQueryStringHas('sort', $name)
                        ->assertQueryStringHas('order', 'asc')
                        ->press("#btn-sort-$name a")
                        ->assertQueryStringHas('sort', $name)
                        ->assertQueryStringHas('order', 'desc')
                        ->press("#btn-sort-$name a")
                        ->assertQueryStringHas('sort', $name)
                        ->assertQueryStringHas('order', 'asc');
            }
        });
    }

    /**
     * Make cases for test.
     *
     * @return array
     */
    public function dataForTest()
    {
        return [
            ['title', 3],
            ['author', 4],
            ['rating', 5],
            ['total_borrowed', 6],
        ];
    }

    /**
     * A Dusk test data.
     *
     * @dataProvider dataForTest
     *
     * @return void
     */
    public function testSortListBooks($name, $columIndex)
    {
        $books = $this->makeData(5);
        $arraySelected = $books->pluck($name)->toArray();

        $this->browse(function (Browser $browser) use ($name, $columIndex, $arraySelected) {
            $browser->loginAs($this->user)
                    ->visit('admin/books')
                    ->resize(1200,1600)
                    ->press("#btn-sort-$name a");

            // Test list Asc
            sort($arraySelected);
            for ($i = 1; $i <= 5; $i++) {
                $selector = "#list-books tbody tr:nth-child($i) td:nth-child($columIndex)";
                $this->assertEquals($browser->text($selector), $arraySelected[$i-1]);
            }

            // Test list Desc
            $browser->press("#btn-sort-$name a");
            rsort($arraySelected);
            for ($i = 1; $i <= 5; $i++) {
                $selector = "#list-books tbody tr:nth-child($i) td:nth-child($columIndex)";
                $this->assertEquals($browser->text($selector), $arraySelected[$i-1]);
            }
        });
    }

    /**
     * A Dusk test data when panigate.
     *
     * @dataProvider dataForTest
     *
     * @return void
     */
    public function testSortListBooksWhenPanigate($name, $columIndex)
    {
        $books = $this->makeData(16);
        $arraySelected = $books->pluck($name)->toArray();
        $this->browse(function (Browser $browser) use ($name, $columIndex, $arraySelected) {
            $browser->loginAs($this->user)
                    ->visit('admin/books')
                    ->resize(1200,1600)
                    ->press("#btn-sort-$name a")
                    ->press('.pagination li:nth-child(4) a');

            // Test list Asc
            sort($arraySelected);
            $arraySortAsc = array_chunk($arraySelected, 10)[1];
            for ($i = 1; $i <= 6; $i++) {
                $selector = "#list-books tbody tr:nth-child($i) td:nth-child($columIndex)";
                $this->assertEquals($browser->text($selector), $arraySortAsc[$i-1]);
            }

            // Test list Desc
            $browser->press("#btn-sort-$name a")
                    ->press('.pagination li:nth-child(4) a');
            rsort($arraySelected);
            $arraySortDesc = array_chunk($arraySelected, 10)[1];
            for ($i = 1; $i <= 6; $i++) {
                $selector = "#list-books tbody tr:nth-child($i) td:nth-child($columIndex)";
                $this->assertEquals($browser->text($selector), $arraySortDesc[$i-1]);
            }
        });
    }

    /**
     * Make data  in database for test.
     *
     * @return void
     */
    public function makeData($row)
    {
        $faker = Faker::create();

        $users = factory(User::class, 2)->create();

        $categories = factory(Category::class, 2)->create();

        $categoryIds = $categories->pluck('id')->toArray();
        $employCodes = $users->pluck('employ_code')->toArray();
        $userIds = $users->pluck('id')->toArray();

        $books = factory(Book::class, $row)->create([
            'category_id' => $faker->randomElement($categoryIds),
            'from_person' => $faker->randomElement($employCodes),
        ])->each(function($book) use ($faker) {
            $book->total_borrowed = $faker->numberBetween(1, 3);
        });
        $bookId = DB::table('books')->pluck('id')->toArray();
        for ($i = 0; $i < $row; $i++) {
            factory(Qrcode::class)->create([
                'book_id' => $faker->unique()->randomElement($bookId)
            ]);
        }

        foreach ($books as $book) {
            factory(Borrow::class, $book->total_borrowed)->create([
                'book_id' => $book->id,
                'user_id' => $faker->randomElement($userIds)
            ]);
        }

        return $books;
    }
}
