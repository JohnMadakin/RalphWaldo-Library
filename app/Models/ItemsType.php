<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemsType extends Model
{
  protected $table = 'itemTypes';
  public function items()
  {
    return $this->hasMany('App\Models\Item');
  }
}
