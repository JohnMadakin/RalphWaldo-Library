<?php
namespace App\Http\Helpers;

use Illuminate\Hashing\BcryptHasher;
use Firebase\JWT\JWT;

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

}