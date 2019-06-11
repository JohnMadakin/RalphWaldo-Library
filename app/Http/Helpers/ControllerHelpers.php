<?php
namespace App\Http\Helpers;

use App\Http\Services\UserService;
use Illuminate\Hashing\BcryptHasher;
use Firebase\JWT\JWT;
use Mockery\Exception;

class ControllerHelpers {
  /**
   * Hashes a string.
   * 
   * @param  string   $password
   * @return string
   */
  public static function hashPassword($password) {
    $bcrypt = new BcryptHasher();
    return $bcrypt->make($password);
  }

  /**
   * create a new token.
   * 
   * @param  Array   $payload
   * @return string
   */
  public static function generateJWT($payload)
  {
    return JWT::encode($payload, env('JWT_SECRET'));
  }

  /**
   * verifies a password string.
   * 
   * @param  string   $password
   * @return string
   */

  public static function verifyPassword($password, $hashPassword){
    $bcrypt = new BcryptHasher();
    return $bcrypt->check($password, $hashPassword);
  }

  /**
   * check of items can be borrowed
   * 
   * @param  string   $password
   * @return string
   */

  public static function checkUserRole($id)
  {
    $getRole = UserService:: findUserRoleById($id)->get();
    if (count($getRole) > 0) { 
      return $getRole[0]->role;
    }
    return false;
  }

  /**
   * deserialize sorting data
   * 
   * @param  string sort
   * @return string
   */

  public static function deserializeSort( $sortString)
  {
    try{
      $allowedFields = ['name', 'email', 'userName', 'dateJoined'];
      $allowedOrder = ['asc','desc'];
      $checkForValidDelimeter = strpos($sortString, '_');
      $splitString = explode('_', $sortString);
      if(!in_array($splitString[0], $allowedFields) || !in_array($splitString[1], $allowedOrder)) {
        return false;
      }
      if ($checkForValidDelimeter != false) {
        $deserialiseValue = array(
          'column' => $splitString[0],
          'order' => $splitString[1],
        );
        return $deserialiseValue;
      }
    }catch(Exception $ex) {
      return false;
    }
  }

}
