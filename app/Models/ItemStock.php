<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;


class ItemStock extends Model
{
  protected $table = 'itemStocks';

  public function items()
  {
    return $this->belongsTo('App\Models\Item');
  }
}
