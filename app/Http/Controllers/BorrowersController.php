<?php
namespace App\Http\Controllers;

use illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\BorrowerService;
use App\Models\ItemStock;
use App\Http\Helpers\ControllerHelpers;
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
    if(ControllerHelpers::checkArrayIsUnique($items)){
      return response()->json([
        'success' => false,
        'message' => 'Duplicate item contents'
      ], 400);
    }
    $checkIfItemsCanBeBorrowed = BorrowerService::confirmItemIsInShelf($items);
    if (count($checkIfItemsCanBeBorrowed) > 0) {
      $checkDuplicateItemId = array();
      foreach ($checkIfItemsCanBeBorrowed as $getItemId) {
        array_push($checkDuplicateItemId, $getItemId->itemId);
      }
      if(ControllerHelpers::checkArrayIsUnique($checkDuplicateItemId)){
        return response()->json([
          'success' => false,
          'message' => 'You can not borrow copies of the same Item'
        ], 400);
      }
      return false;  
    }
    return response()->json([
      'success' => false,
      'message' => 'One or More Items are not Available'
    ], 400);
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
      'items' => 'required|array|min:1',
      'items.*.itemUniqueCode' => 'required|uuid',
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
      return response()->json([
        'success' => false,
        'message' => 'Error borrowing items. Ensure the IDs or ItemUniqueCode(s) are Valid'
      ], 400);
    }
  }

  /**
   * Get items borrowed by user
   * 
   * @param  \App\User   $user 
   * @return mixed
   */
  public function getItemsBorrowedByUser()
  {
    $id = $this->request->id;
    $user = new BorrowerService();
    try {
      $result = $user->getItemsBorrowedByuserId($id);
      if (count($result) > 0) {
        return response()->json([
          'Success' => true,
          'Items' => $result
        ], 200);
      }
      return response()->json([
        'Success' => false,
        'Items' => "no items found for user with ID $id"
      ], 404);

    } catch (Exception $ex) {
      return response()->json([
        'success' => false,
        'message' => 'your request could not be completed'
      ], 500);
    }
  }


  /**
   * return item to the library
   *
   * @param  \Illuminate\Http\Request  $request
   * @return void
   */
  public function returnItems() {
    $this->validate($this->request, [
      'items' => 'required|array|min:1',
      'items.*.itemUniqueCode' => 'required|uuid',
      'items.*.returnStateId' => 'required|integer|min:2',
      'items.*.finesAccrued' => 'required|min:0',

    ]);

    $returnItems = $this->request->input('items');
    try{
      $borrowerService = new BorrowerService();
      $result = $borrowerService->returnItemsBorrowed($returnItems);
      if (!$result) {
        return response()->json([
          'success' => true,
          'items' => $returnItems,
          'message' => 'Items returned successfully'
        ], 200);
      }
    } catch(Exception $ex) {
      return response()->json([
        'success' => false,
        'item' => json_decode($ex->getMessage()),
        'message' => 'Invalid Borrowed item code. Ensure the code is correct and state is valid'
      ], 400);    }


  }

}
