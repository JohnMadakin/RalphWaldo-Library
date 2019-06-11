<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateItemsTable extends Migration
{
    use SoftDeletes;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('description');
            $table->string('isbn');
            $table->integer('authorId')->unsigned();
            $table->foreign( 'authorId')->references('id')->on('authors');
            $table->integer('itemTypeId')->unsigned();
            $table->foreign( 'itemTypeId')->references('id')->on('itemTypes');
            $table->integer('categoryId')->unsigned();
            $table->foreign( 'categoryId')->references('id')->on('categories');
            $table->integer('numberInStock');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
