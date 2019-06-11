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
   * get all users
   * 
   * @param integer $page 
   * @param integer $pageSize
   * @param integer $search
   * @param integer $sortBy
   * @return void
   */

  public function getItems($page, $pageSize, $search, $sortBy, $filters)
  {
    $cat = $filters['category'] ?? '';
    $author = $filters['author'] ?? '';
    $type = $filters[ 'type'] ?? '';
    $items = DB::table('items')->select('title', 'isbn', 'numberInStock as totalNumber', 'itemTypes.name as itemType', 'categories.name as itemCategory', 'authors.name as author', 'items.created_at as dateAdded')
      ->join('itemTypes', 'items.itemTypeId', '=', 'itemTypes.id')
      ->join('categories', 'items.categoryId', '=', 'categories.id')
      ->join('authors', 'items.authorId', '=', 'authors.id')
      ->when($search, function ($query, $search) {
        return $query->where('title', 'ilike', '%' . $search . '%')
          ->orWhere('authors.name', 'ilike', '%' . $search . '%')
          ->orWhere('isbn', 'ilike', '%' . $search . '%');
      })->when($filters['category'] ?? null, function ($query, $cat) {
        return $query->orWhere('categories.name', ucwords($cat))->orWhere('categories.id', ucwords($cat));
      })
      ->when($filters['author'] ?? null, function ($query, $author) {
        return $query->orWhere('authors.name', ucwords($author))->orWhere('authors.id', ucwords($author));
      })->when($filters['type'] ?? null, function ($query, $type) {
        return $query->orWhere('itemTypes.name', strtolower($type))->orWhere('itemTypes.id', strtolower($type));
      })
      ->when($sortBy, function ($query, $sortBy) {
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
