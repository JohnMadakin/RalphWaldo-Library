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
          'authorId' => $items['authorId'],
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

  /**
   * update an item in the DB 
   * 
   * @param Array $items 
   * @return boolean
   */
  public function updateItem($items, $id)
  {
    if ($items) {
        $item = DB::table('items')->where('id', $id)->update([
          'title' => $items['title'],
          'description' => $items['description'],
          'isbn' => $items['isbn'],
          'authorId' => $items['authorId'],
          'itemTypeId' => $items['itemTypeId'],
          'categoryId' => $items['catId'],
        ]);
        return $item;
    }
    return false;
  }


  /**
   * update an item in the DB 
   * 
   * @param Array $items 
   * @return boolean
   */
  public function deleteItemsFromStockById($id)
  {
    // $time = Carbon::now();
    $item = DB::table('itemStocks')->select('itemId')->where('id', $id)->get();
    if(count($item) < 1){
      return false;
    }
    // var_dump($id);    
    $itemId = $item[0]->itemId;
    return DB::transaction(function () use ($id, $itemId) {
      ItemStock::destroy($id);
      DB::table('items')->where('id', $itemId)->decrement('numberInStock');
      return $itemId;
    });
  }


  /**
   * add an item to itemStocks using itemId the DB 
   * 
   * @param Array $items 
   * @return boolean
   */
  public function addToItemStock($items, $itemId)
  {
    if ($itemId) {
      return DB::transaction(function () use ($items, $itemId) {
        $item = ItemStock::create([
          'itemId' => $itemId,
          'itemCondition' => $items['itemCondition'],
          'itemUniqueCode' => Uuid::uuid(),
          'itemStateId' => $items[ 'itemStateId'],
        ]);

        DB::table('items')->where('id', $itemId)->increment('numberInStock');
      return $item;
      });
    }
    return false;
  }


  /**
   * get all users
   * 
   * @param integer $page 
   * @param integer $pageSize
   * @param integer $search
   * @param integer $sortBy
   * @return void
   */

  public function getItems($page, $pageSize, $search, $sortBy)
  {
    $items = DB::table('items')->select('title', 'isbn', 'numberInStock as totalNumber', 'itemTypes.name as itemType', 'categories.name as itemCategory', 'itemStocks.itemUniqueCode as itemCode', 'authors.name as author', 'items.created_at as dateAdded')
      ->join('itemStocks', 'items.id', '=', 'itemStocks.itemId')
      ->join('itemTypes', 'items.itemTypeId', '=', 'itemTypes.id')
      ->join('categories', 'items.categoryId', '=', 'categories.id')
      ->join('authors', 'items.authorId', '=', 'authors.id')
      ->when($search, function ($query, $search) {
        return $query->where('title', 'ilike', '%' . $search . '%')
          ->orWhere('authors.name', 'ilike', '%' . $search . '%')
          ->orWhere('isbn', 'ilike', '%' . $search . '%');
      })->when($sortBy, function ($query, $sortBy) {
        return $query->orderBy($sortBy['column'], $sortBy['order']);
      }, function ($query) {
        return $query->orderBy('name');
      })
      ->paginate($pageSize, ['*'], 'page', $page);
    return $items;
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
