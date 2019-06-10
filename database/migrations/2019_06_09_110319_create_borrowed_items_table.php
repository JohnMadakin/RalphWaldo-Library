<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBorrowedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrowedItems', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('borrowerSessionId')->unsigned();
            $table->foreign( 'borrowerSessionId')->references('id')->on( 'borrowers');
            $table->uuid('itemUniqueCode');
            $table->foreign('itemUniqueCode')->references('itemUniqueCode')->on('itemStocks');
            $table->integer('finesAccrued')->unsigned()->nullable();
            $table->dateTime('dateReturned')->nullable();
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
        Schema::dropIfExists('borrowedItems');
    }
}
