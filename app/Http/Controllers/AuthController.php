<?php
namespace App\Http\Controllers;
// use Firebase\JWT\ExpiredException;
use illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Services\UserService;
use App\Http\Helpers\ControllerHelpers;


class AuthController extends BaseController {
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
   * Authenticate a user and return the token if the provided credentials are correct.
   * 
   * @param  \App\User   $user 
   * @return mixed
   */
  public function authenticate (){
    $this->validate($this->request, [
      'email' => 'email',
      'userName' => 'string',
      'password' => 'required',
    ]);
    $email = $this->request->input('email');
    $userName = $this->request->input('userName');
    $password = $this->request->input('password');
    $user = new UserService();
    $userFound = $user->getUser($userName, $email);
    if( !$userFound){
      return response()->json([
        'success' => false,
        'message' => 'Email or Username not found.',
      ], 400);
    }
    if(ControllerHelpers::verifyPassword($password, $userFound->password)) {
      $userFound['exp'] = time() + 60*60;
      return response()->json([
        'success' => true,
        'token' => ControllerHelpers::generateJWT($userFound)
      ], 200);
    }
    return response()->json([
      'success' => false,
      'message' => 'Email or Password is wrong.'
    ], 400);
  }
}