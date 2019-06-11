<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
  protected $table = 'authors';

  protected $guarded = [
    'id',
  ];

  public function items()
  {
    return $this->hasMany('App\Models\Item');
  }
}
