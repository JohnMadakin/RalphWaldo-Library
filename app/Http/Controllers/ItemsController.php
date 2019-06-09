<?php
namespace App\Http\Controllers;

use illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\ItemService;
use App\Models\ItemStock;
use Exception;

class ItemsController extends BaseController
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

  /**
   * Add an item to the collection
   *
   * @param  \Illuminate\Http\Request  $request
   * @return void
   */

  public function addItems(){
    $this->validate($this->request, [
      'title' => 'required|max:60',
      'description' => 'string|max:255',
      'isbn' => 'string',
      'author' => 'required|max:60',
      'itemTypeId' => 'required|integer|min:1',
      'categoryId' => 'required|integer|min:1',
      'numberInStock' => 'required|integer|min:1',
      'itemCondition' => 'required',
      'itemStateId' => 'required|integer|min:1',
    ]);
    $items = array(
      'title' => $this->request->input('title'),
      'description' => $this->request->input('description'),
      'isbn' => $this->request->input('isbn'),
      'author' =>  $this->request->input('author'),
      'catId' => $this->request->input('categoryId'),
      'numOfItems' => $this->request->input('numberInStock'),
      'itemCondition' => $this->request->input('itemCondition'),
      'itemStateId' => $this->request->input('itemStateId'),
      'itemTypeId' => $this->request->input('itemTypeId'),
    );

    try{
      $item = new ItemService();
      $result = $item->createNewItem($items);

      if ($result) {
        return response()->json([
          'success' => true,
          'itemId' => $result,
          'message' => 'Item Added Successfully',
        ], 201);
      }
      return response()->json([
        'success' => false,
        'message' => 'item could not be added'
      ], 400);

    }catch(Exception $ex){
      var_dump($ex->getMessage());

      return response()->json([
        'success' => false,
        'message' => 'Error Occured Adding Items'
      ], 500);
    }
  }
}
