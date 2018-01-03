<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('qrcode', 30);
            $table->integer('category_id')->unsigned();
            $table->string('title');
            $table->text('description');
            $table->year('year');
            $table->string('author', 100);
            $table->string('picture');
            $table->string('from_person', 10);
            $table->integer('total_rating')->default(0);
            $table->float('rating', 4, 1)->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->index('title');
            $table->index('author');
            $table->index('from_person');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
