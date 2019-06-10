<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowedItemsReport extends Model
{
  protected $table = 'borrowedItemsReports';

  public function borrowedItems()
  {
    return $this->hasOne( 'App\Models\borrowedItem');
  }
}
