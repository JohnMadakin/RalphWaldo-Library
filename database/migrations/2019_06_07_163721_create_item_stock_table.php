<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateItemStockTable extends Migration
{
    use SoftDeletes;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itemStocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('itemId')->unsigned();
            $table->foreign('itemId')->references('id')->on('items');
            $table->uuid('itemUniqueCode')->unique();
            $table->enum('itemCondition', ['New', 'Used']);
            $table->integer('itemStateId');
            $table->foreign( 'itemStateId')->references('id')->on('itemStates');
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
        Schema::dropIfExists('itemStocks');
    }
}
