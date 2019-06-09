<?php

namespace App\Http\Services;
use App\Models\User;
use App\Role;


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

  /**
   * find user role by userid
   * 
   * @param  id $id
   * @return mixed
   */
  public static function findUserRoleById($id)
  {
    if (is_int($id)) {
      return User::find($id)->roles();
    }
    return false;
  }

  /**
   * add a user to the users table .
   * 
   * @param  userName $userName
   * @param email $email
   * @param password $passwword
   * @param address $address
   * @return mixed
   */
  public function addUser( $userName, $email, $name, $password, $address, $roleId)
  {
    if (!empty($userName) && !empty($email) && !empty($name) && !empty($address) && !empty($password)) {
      $user = User::create(
        [
          'userName' => $userName,
          'email' => $email,
          'name' => $name,
          'password' => $password,
          'address' => $address,
        ]);
        $user->roles()->attach($roleId);
        return $user;
    }
    return false;
  }

}