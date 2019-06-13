<?php
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{

  use DatabaseMigrations;
  protected $user;
  protected $token;

  public function testAdminShouldSignIn()
  {
    $this->user = [
      'userName' => 'test1',
      'password' => 'password@1',
    ];
    $this->json('POST', '/login', $this->user)
      ->seeStatusCode(200)
      ->seeJsonStructure([
        'success',
        'token'
      ]);

  }

  public function testShouldNotSigninWithInvalidUsername()
  {
    $this->user = [
      'userName' => 'test10',
      'password' => 'password@1',
    ];
    $this->json('POST', '/login', $this->user)
      ->seeStatusCode(400)
      ->seeJson([
        'success' => false,
        'message'=> 'Email or Username not found.'
      ]);
  }

  public function testShouldNotSigninWithInvalidPassword()
  {
    $this->user = [
      'userName' => 'test1',
      'password' => 'password@2',
    ];
    $this->json('POST', '/login', $this->user)
      ->seeStatusCode(400)
      ->seeJson([
        'success' => false,
        'message' => 'Email or Password is wrong.'
      ]);
  }



  /**
   * A basic test example.
   *
   * @return void
   */
  public function testShouldCreateNewUser()
  {
    $params = [
      'userName' => 'test2',
      'password' => 'password@1',
      'email' => 'test2@test.com',
      'address' => 'test 123 lagos',
      'firstName' => 'test',
      'lastName' => 'test'
    ];
    $response = $this->call('POST', '/login',[
      'userName' => 'test1',
      'password' => 'password@1',
    ]);    
    $responseArray = explode('"', $response->content());
    $this->token = $responseArray[5];
    $this->json('POST', '/users', $params, ['Authorization' => $this->token])
      ->seeStatusCode(201)
      ->seeJson([
        'success' => true,
        'message' => 'New User Created'
      ]);
    $this->assertEquals(12, User::count());
  }

  public function testShouldNotCreateNewUserWithInvalidInputs()
  {
    $params = [
      'userName' => 'test1',
      'password' => 'password@1',
      'email' => 'test@test.com',
      'address' => 'test 123 lagos',
      'firstName' => 'test',
      'lastName' => 'test'
    ];
    $response = $this->call('POST', '/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $responseArray = explode('"', $response->content());
    $this->token = $responseArray[5];
    $this->json('POST', '/users', $params, ['Authorization' => $this->token])
      ->seeStatusCode(422)
      ->seeJson([
        'email' => [ "The email has already been taken."],
        'userName' => [ "The user name has already been taken."]
      ]);
    $this->assertEquals(11, User::count());
  }

  public function testShouldGetAllRegisteredUsers()
  {
    $response = $this->call('POST', '/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $responseArray = explode('"', $response->content());
    $this->token = $responseArray[5];
    $this->json('GET', '/users', [], ['Authorization' => $this->token])
      ->seeStatusCode(200)
      ->seeJson([
        'success' => true,
        'per_page' => 10,
        'total' => 11,
      ]);
    $this->assertEquals(11, User::count());
  }

  public function testShouldRequestForValidSortParams()
  {
    $response = $this->call('POST', '/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $responseArray = explode('"', $response->content());
    $this->token = $responseArray[5];
    $this->json('GET', '/users?sort=lord', [], ['Authorization' => $this->token])
      ->seeStatusCode(400)
      ->seeJson([
        'success' => false,
        'message' => 'Please enter a valid sort params'
      ]);
  }

  public function testShouldReturnSearchResultsForUsers()
  {
    $response = $this->call('POST', '/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $responseArray = explode('"', $response->content());
    $this->token = $responseArray[5];
    $this->json('GET', '/users?search=test', [], ['Authorization' => $this->token])
      ->seeStatusCode(200)
      ->seeJsonStructure([
        'success',
        'users'
      ]);
  }

  public function testShouldReturnPagenatedResultsForUsers()
  {
    $response = $this->call('POST', '/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $responseArray = explode('"', $response->content());
    $this->token = $responseArray[5];
    $this->json('GET', '/users?page=1&pageSize=5', [], ['Authorization' => $this->token])
      ->seeStatusCode(200)
      ->seeJson([
        'success' => true,
        'per_page' => '5',
        'last_page' => 3
      ]);
  }



}
