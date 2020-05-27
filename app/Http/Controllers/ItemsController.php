<?php
namespace App\Http\Controllers;

use illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\ItemService;
use App\Http\Helpers\ControllerHelpers;
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
   * update an item to the collection
   *
   * @param  \Illuminate\Http\Request  $request
   * @return void
   */
  public function updateItems(){
    $this->validate($this->request, [
      'title' => 'required|max:255',
      'description' => 'string|max:255',
      'isbn' => 'string',
      'authorId' => 'integer|min:1',
      'itemTypeId' => 'integer|min:1',
      'categoryId' => 'integer|min:1',
    ]);
    $items = array(
      'title' => $this->request->input('title'),
      'description' => $this->request->input('description'),
      'isbn' => $this->request->input('isbn'),
      'authorId' =>  $this->request->input('authorId'),
      'catId' => $this->request->input('categoryId'),
      'itemTypeId' => $this->request->input('itemTypeId'),
    );
    $id = $this->request->id;
    try {
      $item = new ItemService();
      $result = $item->updateItem($items, $id);
      if ($result) {
        return response()->json([
          'success' => true,
          'message' => 'Item Updated Successfully',
        ], 200);
      }
      return response()->json([
        'success' => false,
        'message' => 'item could not be updated'
      ], 400);
    } catch (Exception $ex) {
      return response()->json([
        'success' => false,
        'message' => 'Error Occured updating Items. Ensure the IDs are Valid'
      ], 400);
    }
  }

  /**
   * Add an item to the stock
   * @param  \Illuminate\Http\Request  $request
   * @return void
   */

  public function addItemStock()
  {
    $this->validate($this->request, [
      'itemCondition' => 'required|string',
      'itemStateId' => 'required|integer|min:1',
    ]);
    $items = array(
      'itemStateId' => $this->request->input('itemStateId'),
      'itemCondition' => $this->request->input( 'itemCondition'),
    );
    $id = $this->request->id;
    try {
      $item = new ItemService();
      $result = $item->addToItemStock($items, $id);

      if ($result) {
        return response()->json([
          'success' => true,
          'data' => [
            'itemId' => $result,
          ],
          'message' => 'Item Added Successfully',
        ], 200);
      }
      return response()->json([
        'success' => false,
        'message' => 'item could not be added'
      ], 400);
    } catch (Exception $ex) {
      return response()->json([
        'success' => false,
        'message' => 'Error Occured Adding Items. Ensure the IDs are Valid'
      ], 400);
    }
  }

  /**
   * Add an item to the stock
   * @param  \Illuminate\Http\Request  $request
   * @return void
   */

  public function deleteItemsFromStock()
  {
    $id = $this->request->id;
    try {
      $item = new ItemService();
      $result = $item->deleteItemsFromStockById($id);
      if ($result) {
        return response()->json([
          'success' => true,
          'message' => 'Item Deleted'
        ], 204);
      }
      return response()->json([
        'success' => false,
        'message' => 'item not found'
      ], 404);
    } catch (Exception $ex) {
      var_dump($ex->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error Occured deleting Items. Ensure the IDs are Valid'
      ], 400);
    }
  }


  /**
   * get Items from the collection
   *
   * @param  \Illuminate\Http\Request  $request
   * @return void
   */
  public function getItems()
  {
    $pageSize = $this->request->query('pageSize') ?? 30;
    $page = $this->request->query('page') ?? 1;
    $sortBy = $this->request->query('sort') ?? 'title_asc';
    $search = $this->request->has('search') ? $this->request->query('search') : null;
    $allowedFields = ['title', 'author', 'isbn'];
    $allowedOrder = ['asc', 'desc'];
    $sort = ControllerHelpers::deserializeSort($sortBy, $allowedFields, $allowedOrder);
    if (!$sort) {
      return response()->json([
        'success' => false,
        'message' => 'Please enter a valid sort params'
      ], 400);
    }

    $items = new ItemService();
    try {
      $result = $items->getItems($page, $pageSize, $search, $sort);
      if ($result) {
        return response()->json([
          'success' => true,
          'items' => $result
        ], 200);
      }
    } catch (Exception $ex) {
        return response()->json([
          'success' => false,
          'message' => 'your request could not be completed'
        ], 400);
    }

  }

  /**
   * Add an item to the collection
   *
   * @param  \Illuminate\Http\Request  $request
   * @return void
   */

  public function addItems(){
    $this->validate($this->request, [
      'title' => 'required|max:255',
      'description' => 'string|max:255',
      'isbn' => 'string',
      'authorId' => 'required|integer|min:1',
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
      'authorId' =>  $this->request->input( 'authorId'),
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
          'data' => [
            'itemId' => $result,
          ],
          'message' => 'Item Added Successfully',
        ], 201);
      }
      return response()->json([
        'success' => false,
        'message' => 'item could not be added'
      ], 400);

    }catch(Exception $ex){
      return response()->json([
        'success' => false,
        'message' => 'Error Occured Adding Items. Ensure the IDs are Valid'
      ], 400);
    }
  }
}
