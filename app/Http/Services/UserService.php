<?php

namespace App\Http\Services;
use App\User;

class UserService {
  /**
   * Get a user from the DB using userName or email as optional parameters.
   * 
   * @param  userName $userName
   * @param email $email
   * @param password $passwword
   * @return mixed
   */
  public function getUser($userName = Null, $email = Null) {
    if (!empty($userName)) {
      return User::where('userName', $userName)->first();
    }
    return User::where('email', $email)->first();
  }

  /**
   * find user by id
   * 
   * @param  id $id
   * @return mixed
   */
  public static function findUserById($id)
  {
    if(is_int($id)){
      return User::find($id);
    }
    return false;
  }

}