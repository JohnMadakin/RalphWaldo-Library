<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemStock extends Model
{
  use SoftDeletes;
  protected $table = 'itemStocks';

  /**
   * The attributes that are not mass assignable.
   *
   * @var array
   */
  protected $guarded = [
    'id',
  ];


  public function items()
  {
    return $this->belongsTo('App\Models\Item');
  }
}
