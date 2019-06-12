<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Models\BorrowedItem;
use App\Models\Borrower;
use Illuminate\Support\Carbon;
use App\Models\ItemStock;

class BorrowerService {

  /**
   * post an item to the DB using
   * 
   * @param Array $items 
   * @return void
   */
  public function borrowItems($borrowItems)
  {
    if ($borrowItems) {
      return DB::transaction(function () use ($borrowItems) {
        $borrowerSession = Borrower::create([
          'libraryCardId' => $borrowItems['libraryCardId'],
          'librarianId' => $borrowItems['librarianId'],
        ]);
        $itemData = BorrowerService::formatBorrowedItems($borrowItems['items'], $borrowerSession->id);
        BorrowedItem::insert($itemData);
        $dataToUpdate = BorrowerService:: updateItemStocksCode($borrowItems['items']);
        ItemStock::whereIn('itemUniqueCode', $dataToUpdate)->update([ 'itemStateId' => 3 ]);
        return $borrowerSession->id;
      });
    }
    return false;
  }

  /**
   * post an item to the DB using
   * 
   * @param Array $items 
   * @return void
   */
  public static function formatBorrowedItems($items, $id) {
    $borrowedItems = array();
    $time = Carbon::now();
    foreach($items as $item){
      array_push($borrowedItems,
        [
          'borrowerSessionId' => $id,
          'itemUniqueCode' => $item[ 'itemUniqueCode'],
          'updated_at' => $time,
          'created_at' => $time,
        ]
      );
    }
    return $borrowedItems;
  }

  /**
   * Get items borrowed by user 
   * 
   * @param  \App\User   $user 
   * @return mixed
   */
  public function getItemsBorrowedByuserId($id)
  {
    if($id){
      $result = DB::table('borrowers')->select('borrowedItems.itemUniqueCode','borrowers.created_at as dateBorrowed', 'itemStocks.itemCondition', 'items.title','items.isbn', 'authors.name as author')
      ->join('borrowedItems', 'borrowers.id', '=', 'borrowedItems.borrowerSessionId')
      ->join('itemStocks', 'borrowedItems.itemUniqueCode', '=', 'itemStocks.itemUniqueCode')
      ->join('items', 'itemStocks.itemId', '=', 'items.id')
      ->join('authors', 'items.authorId', '=', 'authors.id')
      ->where( 'borrowers.libraryCardId', $id)
      ->get();
      return $result;
    }
    return false;
  }


  /**
   * update an itemStocks to reflect item has been borrowed
   * 
   * @param Array $items 
   * @return void
   */
  public static function updateItemStocksCode($items)
  {
    $borrowedItems = array();
    foreach ($items as $item) {
      array_push(
        $borrowedItems, $item['itemUniqueCode']
      );
    }
    return $borrowedItems;
  }

  /**
   * checks itemStocks for items that can be borrowed
   * 
   * @param Array $items 
   * @return void
   */
  public static function confirmItemIsInShelf($items)
  {
    $dataToUpdate = BorrowerService:: updateItemStocksCode($items);
    $nonBorrowableItems = ItemStock::whereIn('itemUniqueCode', 
    $dataToUpdate)->where('itemStateId', '=', 4)
    ->get();
    return $nonBorrowableItems;
  }

  /**
   * Ensure they are not the same item
   * 
   * @param Array $items 
   * @return void
   */
  public static function checkIfSameItem($items)
  {
    $dataToUpdate = BorrowerService:: updateItemStocksCode($items);
    $sameItems = ItemStock::whereIn('itemUniqueCode', 
    $dataToUpdate)->groupBy('itemId')->get();
    return $sameItems;
  }

  /**
   * update borrowItems to reflect items have been returned
   * 
   * @param Array $items 
   * @return void
   */
  public function returnItemsBorrowed($items)
  {
    if (is_array($items)) {
      return DB::transaction(function () use ($items) {
        foreach($items as $item){
          BorrowedItem::where('itemUniqueCode', $item['itemUniqueCode'])->update([
            'finesAccrued' => $item['finesAccrued'],
            'returnStateId' => $item['returnStateId'],
          ]);
          ItemStock::where('itemUniqueCode', $item['itemUniqueCode'])->update([
          'itemStateId' => $item['returnStateId'],
        ]);
        }
        return true;
      });
    }
    return false;
  }



}
