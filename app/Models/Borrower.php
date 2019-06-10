<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
  
  /**
   * The attributes that are not mass assignable.
   *
   * @var array
   */
  protected $guarded = [
    'id',
  ];

  protected $table = 'borrowers';

  public function borrowedItems()
  {
    return $this->hasMany('App\Models\BorrowedItem');
  }
  public function user()
  {
    return $this->belongsTo('App\Models\User');
  }

}
