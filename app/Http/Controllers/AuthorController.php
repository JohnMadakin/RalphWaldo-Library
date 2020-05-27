<?php
namespace App\Http\Controllers;
// use Firebase\JWT\ExpiredException;
use illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\AuthorService;
use App\Http\Helpers\ControllerHelpers;
use Exception;


class AuthorController extends BaseController
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
   * creates a new user
   * 
   * @param  \App\Author   $author 
   * @return mixed
   */
  public function createNewAuthor()
  {
    $this->validate($this->request, [
      'name' => 'required|regex:/^[a-z ,.\'-]+$/i |max:100'
    ]);
    $name = trim($this->request->input('name'));
    $author = new AuthorService();
    try {
      $authorDetail = $author->addAuthor($name);
      if ( $authorDetail) {
        return response()->json([
          'success' => true,
          'data' => ['id' => $authorDetail ],
          'message' => 'New Author Created'
        ], 201);
      }
    } catch (Exception $ex) {
      return response()->json([
        'success' => false,
        'message' => 'Author already Exists'
      ], 400);
    }
  }

  /**
   * Get all users
   * 
   * @param  \App\User   $user 
   * @return mixed
   */
  public function getAuthors()
  {
    $pageSize = $this->request->query('pageSize') ?? 10;
    $page = $this->request->query('page') ?? 1;
    $sortBy = $this->request->query('sort') ?? 'name_asc';
    $authorQuery = $this->request->has('author') ? $this->request->query('author') : null;
    $allowedFields = ['name','dateAdded'];
    $allowedOrder = ['asc', 'desc'];

    $sort = ControllerHelpers::deserializeSort($sortBy, $allowedFields, $allowedOrder);
    if (!$sort) {
      return response()->json([
        'success' => false,
        'message' => 'Please enter a valid sort params'
      ], 400);
    }
    $author = new AuthorService();
    try {
      if($pageSize == 'all'){
        $result = $author->getAllAuthors();
        return response()->json([
          'success' => true,
          'authors' => $result
        ], 200);
      }
      $result = $author->getAuthors($page, $pageSize, $authorQuery, $sort);
      if ($result) {
        return response()->json([
          'success' => true,
          'authors' => $result
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
   * Get all books by author
   * 
   * @param  \App\User   $user 
   * @return mixed
   */
  public function getItemsByAuthor()
  {
    $id = $this->request->id;
    $author = new AuthorService();
    try {
      $result = $author->getItemsByAuthorId($id);
      if ($result) {
        return response()->json([
          'Success' => true,
          'Items' => $result
        ], 200);
      }
      return response()->json([
          'Success' => false,
          'message' => 'Author not found'
        ], 404);

    } catch (Exception $ex) {
      return response()->json([
        'success' => false,
        'message' => 'your request could not be processed'
      ], 500);
    }
  }

}
