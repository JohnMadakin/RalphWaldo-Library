<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBorrowedItemsReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrowedItemsReports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('borrowedItemId')->unsigned()->nullable();
            $table->foreign( 'borrowedItemId')->references('id')->on( 'borrowedItems');
            $table->string('returnedItemReport');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('borrowedItemsReports');
    }
}
