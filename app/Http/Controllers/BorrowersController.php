<?php
namespace App\Http\Controllers;

use illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\BorrowerService;
use App\Models\ItemStock;
use Exception;

class BorrowersController extends BaseController
{

  /**
   * The request instance.
   *
   * @var \Illuminate\Http\Request
   */
  private $request;

  /**
   * Create a new controller instance.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return void
   */
  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  public static function cantBorrowItems($items) {
    $checkIfItemsCanBeBorrowed = BorrowerService::confirmItemIsInShelf($items);
    if (count($checkIfItemsCanBeBorrowed) > 0) {
      $notInShelf = array();
      foreach ($checkIfItemsCanBeBorrowed as $cantBorrow) {
        array_push($notInShelf, $cantBorrow->itemUniqueCode);
      }
      return response()->json([
        'success' => false,
        'data' => $notInShelf,
        'message' => 'These Items are not Available'
      ], 400);
    }
    return false;
  }

  /**
   * Borrow an item from the library
   *
   * @param  \Illuminate\Http\Request  $request
   * @return void
   */
  public function borrowItems() {
    $this->validate($this->request, [
      'libraryCardId' => 'required|integer|min:1',
      'items' => 'required|array',
    ]);
    $cantBorrow = BorrowersController::cantBorrowItems( $this->request->input('items'));
    if($cantBorrow){
      return $cantBorrow;
    }
    $librarianId = $this->request->auth->id;
    $borrowItems = array(
      'libraryCardId' => $this->request->input( 'libraryCardId'),
      'librarianId' => $librarianId,
      'items' => $this->request->input('items'),
    );
    try{
      $borrowerService = new BorrowerService();
      $result = $borrowerService->borrowItems($borrowItems);
      if ($result) {
        return response()->json([
          'success' => true,
          'data' => [
            'borrowerSessionId' => $result,
          ],
          'message' => 'Items have been Lent Successfully',
        ], 201);
      }
      return response()->json([
        'success' => false,
        'message' => 'could not borrow items'
      ], 400);
    } catch (Exception $ex) {
      // var_dump($ex->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error borrowing items. Ensure the IDs or ItemUniqueCode(s) are Valid'
      ], 400);
    }
  }
}
