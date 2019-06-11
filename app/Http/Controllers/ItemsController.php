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
  }

  /**
   * get Items from the collection
   *
   * @param  \Illuminate\Http\Request  $request
   * @return void
   */
  public function getItems()
  {
    $pageSize = $this->request->query('pageSize') ?? 10;
    $page = $this->request->query('page') ?? 1;
    $sortBy = $this->request->query('sort') ?? 'title_asc';
    $search = $this->request->has('search') ? $this->request->query('search') : null;
    $filters = $this->request->all();
    $allowedFields = ['title', 'author', 'isbn'];
    $allowedOrder = ['asc', 'desc'];
    $filterTerms = ['category', 'type', 'author'];
    $sort = ControllerHelpers::deserializeSort($sortBy, $allowedFields, $allowedOrder);
    $filterValues = ControllerHelpers:: extractFilterValuesFromParams($filters, $filterTerms);
    if (!$sort) {
      return response()->json([
        'success' => false,
        'message' => 'Please enter a valid sort params'
      ], 400);
    }

    $items = new ItemService();
    try {
      $result = $items->getItems($page, $pageSize, $search, $sort, $filters);
      if ($result) {
        return response()->json([
          'success' => true,
          'users' => $result
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
      'title' => 'required|max:60',
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
