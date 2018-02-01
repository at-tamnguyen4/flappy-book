<?php

namespace Tests\Browser\Pages\Backend\Borrows;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Model\User;
use App\Model\Category;
use App\Model\Borrow;
use App\Model\Book;
use Faker\Factory as Faker;

class SearchBorrowsTest extends DuskTestCase
{
    use DatabaseMigrations;
    const NUMBER_RECORD_CREATE = 6;

    /**
     * Override function setUp()
     *
     * @return void
     */
    public function setUp()
    {
       parent::setUp();
       $this->makeData(self::NUMBER_RECORD_CREATE);
    }

     /**
     * A Dusk test looking input search null with select name
     *
     * @return void
     */
    public function testSeeInputNullSelectName()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/admin/borrows')
                ->resize(1200,1600)
                ->assertSee('List Borrower')
                ->assertInputValue('search', '')
                ->select('filter', 'name')
                ->click('#btn-search')
                ->visit('/admin/borrows?search=&filter=name')
                ->assertQueryStringHas('search', '')
                ->assertQueryStringHas('filter', 'name')->screenshot(1);
            $elements = $browser->elements('#list-borrows tbody tr');
            $this->assertCount(self::NUMBER_RECORD_CREATE + 1, $elements);
        });
    }

    /**
     * A Dusk test looking input search null with select title
     *
     * @return void
     */
    public function testSeeInputNullSelectBook(){
        $this->browse(function (Browser $browser){
            $browser->loginAs($this->user)
                ->visit('/admin/borrows')
                ->resize(1200,1600)
                ->assertSee('List Borrower')
                ->assertInputValue('search', '')
                ->select('filter', 'title')
                ->click('#btn-search')
                ->visit('/admin/borrows?search=&filter=title')
                ->assertQueryStringHas('search', '')
                ->assertQueryStringHas('filter', 'title')->screenshot(2);
            $elements = $browser->elements('#list-borrows tbody tr');
            $this->assertCount(self::NUMBER_RECORD_CREATE + 1, $elements);
        });
    }

    /**
     * A Dusk test when input search username with select name not result
     *
     * @return void
     */
    public function testSearchSelectNameNotResult(){
        $this->browse(function (Browser $browser){
            $browser->loginAs($this->user)
                ->visit('/admin/borrows')
                ->resize(1200,1600)
                ->assertSee('List Borrower')
                ->type('search', 'Hello')
                ->select('filter', 'name')
                ->click('#btn-search')
                ->visit('/admin/borrows?search=Hello&filter=name')
                ->assertQueryStringHas('search', 'Hello')
                ->assertQueryStringHas('filter', 'name')->screenshot(3);
            $elements = $browser->elements('#list-borrows tbody tr');
            $this->assertCount(0, $elements);
        });
    }

    /**
     * A Dusk test when input search titlebook with select title not result
     *
     * @return void
     */
    public function testSearchSelectBookNotResult(){
        $this->browse(function (Browser $browser){
            $browser->loginAs($this->user)
                ->visit('/admin/borrows')
                ->resize(1200,1600)
                ->assertSee('List Borrower')
                ->type('search', 'Hello')
                ->select('filter', 'title')
                ->click('#btn-search')
                ->visit('/admin/borrows?search=Hello&filter=title')
                ->assertQueryStringHas('search', 'Hello')
                ->assertQueryStringHas('filter', 'title')->screenshot(4);
            $elements = $browser->elements('#list-borrows tbody tr');
            $this->assertCount(0, $elements);
        });
    }

    /**
     * A Dusk test when input search username incorrect with select name
     *
     * @return void
     */
    public function testSearchSelectName(){
        $this->browse(function (Browser $browser){
            $browser->loginAs($this->user)
                ->visit('/admin/borrows')
                ->resize(1200,1600)
                ->assertSee('List Borrower')
                ->type('search', 'Minh Dao T.')
                ->select('filter', 'name')
                ->click('#btn-search')
                ->visit('/admin/borrows?search=Minh Dao T.&filter=name')
                ->assertQueryStringHas('search', 'Minh Dao T.')
                ->assertQueryStringHas('filter', 'name')->screenshot(5);
            $elements = $browser->elements('#list-borrows tbody tr');
            $this->assertCount(1, $elements);
        });
    }

    /**
     * A Dusk test when input search titlebook incorrect with select title
     *
     * @return void
     */
    public function testSearchSelectBook(){
        $this->browse(function (Browser $browser){
            $browser->loginAs($this->user)
                ->visit('/admin/borrows')
                ->resize(1200,1600)
                ->assertSee('List Borrower')
                ->type('search', 'Java')
                ->select('filter', 'title')
                ->click('#btn-search')
                ->visit('/admin/borrows?search=Java&filter=title')
                ->assertQueryStringHas('search', 'Java')
                ->assertQueryStringHas('filter', 'title')->screenshot(6);
            $elements = $browser->elements('#list-borrows tbody tr');
            $this->assertCount(1, $elements);
        });
    }
    
    /**
     * A Data test for users, books and borrows
     *
     * @return void
     */
    public function makeData($row)
    {
        $faker = Faker::create();
        $users = factory(User::class, 4)->create();
        $userId = $users->pluck('id')->toArray();
        $employeeCode = $users->pluck('employ_code')->toArray();
        $categoryId = factory(Category::class, 2)->create()->pluck('id')->toArray();
        for ($i = 0; $i < $row; $i++) {
            $books[] = factory(Book::class)->create([
                'from_person' => $faker->randomElement($employeeCode),
                'category_id' => $faker->randomElement($categoryId),
            ]);
        }
        $bookId = array_pluck($books, 'id');
        for ($i = 0; $i < $row; $i++) {
            factory(Borrow::class)->create([
                'book_id' => $faker->randomElement($bookId),
                'user_id' => $faker->randomElement($userId),
                'status' => Borrow::BORROWING
            ]);
        }
        factory(Book::class)->create([
            'from_person' => $faker->randomElement($employeeCode),
            'category_id' => $faker->randomElement($categoryId),
            'id' => 7,
            'title' => 'Java'
        ]);
        factory(Borrow::class)->create([
                'book_id' => 7,
                'user_id' => 1,
                'status' => Borrow::BORROWING
            ]);
    }
}
