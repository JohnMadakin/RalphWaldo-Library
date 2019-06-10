<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class borrowedItem extends Model
{
  protected $table = 'borrowedItems';

  public function borrowedItemsReport()
  {
    return $this->belongsTo( 'App\Models\BorrowedItemsReport');
  }

  public function borrower()
  {
    return $this->belongsTo('App\Models\Borrower');
  }
}
