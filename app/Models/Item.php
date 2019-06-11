<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
  /**
   * The attributes that are not mass assignable.
   *
   * @var array
   */
  protected $guarded = [
    'id',
  ];

  public function itemStock()
  {
    return $this->hasMany('App\Models\ItemStock');
  }

  public function category()
  {
    return $this->belongsTo('App\Models\Category');
  }

  public function itemTypes()
  {
    return $this->belongsTo('App\Models\ItemsType');
  }

  public function author()
  {
    return $this->belongsTo('App\Models\Author');
  }


}
