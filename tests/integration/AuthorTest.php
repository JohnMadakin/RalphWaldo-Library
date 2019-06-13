<?php
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthorTest extends TestCase
{

  use DatabaseMigrations;
  protected $user;
  protected $token;

  public function testShouldCreateNewAuthor()
  {
    $params = [
      'name' => 'test2',
    ];
    $response = $this->call('POST', '/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $responseArray = explode('"', $response->content());
    $this->token = $responseArray[5];
    $this->json('POST', '/authors', $params, ['Authorization' => $this->token])
      ->seeStatusCode(201)
      ->seeJson([
        'success' => true,
        'message' => 'New Author Created'
      ]);
  }
}