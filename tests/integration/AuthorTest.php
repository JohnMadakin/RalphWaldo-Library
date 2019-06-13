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
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $this->json('POST', '/api/v1/authors', $params, ['Authorization' => $token])
      ->seeStatusCode(201)
      ->seeJson([
        'success' => true,
        'message' => 'New Author Created'
      ]);
  }

  public function testShouldGetAllAuthors()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $this->json('GET', '/api/v1/authors', [], ['Authorization' => $token])
      ->seeStatusCode(200)
      ->seeJsonStructure([
        'success',
        'authors' => ['data' => [['name']]]
      ]);
  }

  public function testShouldGetAllAuthorsSortedInDescendingOrder()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $this->json('GET', '/api/v1/authors?sort=name_desc', [], ['Authorization' => $token])
      ->seeStatusCode(200)
      ->seeJsonStructure([
        'success',
        'authors'=> ['data' => [ ['name'] ] ]
      ]);
  }

  public function testShouldNotGetAuthorsWithInvalidSortParams()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $this->json('GET', '/api/v1/authors?sort=jghj', [], ['Authorization' => $token])
      ->seeStatusCode(400)
      ->seeJson([
      'success' => false,
      'message' => 'Please enter a valid sort params'
      ]);
  }

  public function testShouldGetAuthorsWithWithPagination()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;

    $this->json('GET', '/api/v1/authors?page=3&pageSize=4', [], ['Authorization' => $token])
      ->seeStatusCode(200)
      ->seeJson([
        'success' => true,
      ]);
  }

  public function testShouldGetItemsByAuthors()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;

    $this->json('GET', '/api/v1/authors/1/items', [], ['Authorization' => $token])
      ->seeStatusCode(200)
      ->seeJson([
        'Success' => true,
      ]);
  }

}