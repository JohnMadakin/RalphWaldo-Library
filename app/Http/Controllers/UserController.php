<?php
namespace App\Http\Controllers;
// use Firebase\JWT\ExpiredException;
use illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\UserService;
use App\Http\Helpers\ControllerHelpers;
use Exception;


class UserController extends BaseController
{
  /**
   * The request instance.
   *
   * @var \Illuminate\Http\Request
   */
  private $request;
  private $userRole;
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
   * @param  \App\User   $user 
   * @return mixed
   */
  public function createNewUser()
  {
    $this->validate($this->request, [
      'email' => 'email|max:255',
      'userName' => 'string|max:45',
      'password' => 'required|min:8',
      'firstName' => 'required|max:50',
      'lastName' => 'required|max:50',
      'address' => 'required|max:255'
    ]);
    $email = $this->request->input('email');
    $userName = $this->request->input('userName');
    $name = trim($this->request->input('firstName')) . ' ' . trim($this->request->input('lastName'));
    $password = ControllerHelpers::hashPassword($this->request->input('password'));
    $address = $this->request->input('address');
    $user = new UserService();
    $this->userRole = 4;
    try {
      $userDetail = $user->addUser(trim($userName), trim($email), $name, $password, $address, $this->userRole);
      if ($userDetail) {
        $userDetail['exp'] = time() + 60 * 60;
        return response()->json([
          'success' => true,
          'message' => 'New User Created'
        ], 201);
      }
    } catch (Exception $ex) {
      return response()->json([
        'success' => false,
        'message' => 'Email or Username Already in use'
      ], 400);
    }
    return response()->json([
      'success' => false,
      'message' => 'New User could not be created'
    ], 400);
  }

  /**
   * Get all users
   * 
   * @param  \App\User   $user 
   * @return mixed
   */
  public function getUsers()
  {
    $pageSize = $this->request->query('pageSize') ?? 10;
    $page = $this->request->query('page') ?? 1;
    $sortBy = $this->request->query('sort') ?? 'name_asc';
    $search = $this->request->has('search') ? $this->request->query('search') : null;
    $allowedFields = ['name', 'email', 'userName', 'dateJoined'];
    $allowedOrder = ['asc','desc'];

    $sort = ControllerHelpers::deserializeSort($sortBy, $allowedFields, $allowedOrder);
    if(!$sort) {
      return response()->json([
        'success' => false,
        'message' => 'Please enter a valid sort params'
      ], 400);
    }
    $users = new UserService();
    try {
      $result = $users->getAllUsers($page, $pageSize, $search, $sort);
      if ( $result) {
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
}
