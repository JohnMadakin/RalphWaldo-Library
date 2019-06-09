<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemState extends Model
{
  protected $table = 'itemStates';

  public function itemStock()
  {
    return $this->hasMany('App\Models\ItemStock');
  }
}
