<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Models\BorrowedItemsReport;
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
    // var_dump($borrowItems['items']);
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
    $dataToUpdate)->where('itemStateId', '<>', 4)->get();
    return $nonBorrowableItems;
  }
}
