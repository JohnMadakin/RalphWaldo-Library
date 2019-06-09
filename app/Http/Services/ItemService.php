<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\ItemStock;
use Faker\Provider\Uuid;
use Illuminate\Support\Carbon;

class ItemService
{

  /**
   * post an item to the DB using
   * 
   * @param Array $items 
   * @return void
   */
  public function createNewItem($items)
  {
    if($items){
      return DB::transaction(function () use ($items){
        $item = Item::create([
          'title' => $items['title'],
          'description' => $items['description'],
          'isbn' => $items['isbn'],
          'author' => $items['author'],
          'itemTypeId' => $items['itemTypeId'],
          'categoryId' => $items['catId'],
          'numberInStock' => $items[ 'numOfItems'],
        ]);
          
        $itemData = ItemService::generateItemStockData( $items['numOfItems'], $item->id, $items[ 'itemCondition'], $items[ 'itemStateId']);
        ItemStock::insert($itemData);
        return $item->id;
      });
    }
    return false;

  }

  public static function generateItemStockData($numOfItems, $itemId, $itemCondition, $itemStateId){
    $time = Carbon::now();
    $itemInStock = array();
    for ($num = 1; $num <= $numOfItems; $num++) {
      array_push($itemInStock, [
        'itemId' => $itemId, 
        'itemUniqueCode' => Uuid::uuid(),
        'itemCondition' => $itemCondition,
        'itemStateId' => $itemStateId,
        'updated_at' => $time,
        'created_at' => $time,
        ]);
    };
    return $itemInStock;
  }

}
