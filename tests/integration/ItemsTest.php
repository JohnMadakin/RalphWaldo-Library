<?php
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ItemsTest extends TestCase
{

  use DatabaseMigrations;
  protected $user;
  protected $token;

  public function testShouldCreateNewItem()
  {
    $items = [
      'title' => 'test',
      'description' => 'test',
      'isbn' => '7665-423-85-978',
      'authorId' =>  2,
      'categoryId' => 3,
      'numberInStock' => 2,
      'itemCondition' => 'New',
      'itemStateId' => 4,
      'itemTypeId' => 1,
    ];

    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $this->json('POST', '/api/v1/items', $items, ['Authorization' => $token])
      ->seeStatusCode(201)
      ->seeJson([
        'success' => true,
        'message' => 'Item Added Successfully'
      ]);
  }

  public function testShouldUpdateItem()
  {
    $items = [
      'title' => 'test test tes',
      'description' => 'test test',
      'isbn' => '7665-423-85-978',
      'authorId' =>  2,
      'categoryId' => 1,
      'itemTypeId' => 2,
    ];

    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $this->json('PUT', '/api/v1/items/1', $items, ['Authorization' => $token])
      ->seeStatusCode(200)
      ->seeJson([
          'success' => true,
          'message' => 'Item Updated Successfully',
      ]);
  }


  public function testShouldNotAddItemIfAnyOfTheRequiredIdsAreWrong()
  {
    $items = array(
      'title' => 'test',
      'description' => 'test',
      'isbn' => '7665-423-85-978',
      'authorId' =>  2,
      'categoryId' => 3,
      'numberInStock' => 2,
      'itemCondition' => 'New',
      'itemStateId' => 4,
      'itemTypeId' => 1222,
    );


    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $this->json('POST', '/api/v1/items', $items, ['Authorization' => $token])
      ->seeStatusCode(400)
      ->seeJson([
      'success' => false,
      'message' => 'Error Occured Adding Items. Ensure the IDs are Valid'
      ]);
  }

  public function testShouldGetAllItems()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $this->json('GET', '/api/v1/items', [], ['Authorization' => $token])
      ->seeStatusCode(200)
      ->seeJsonStructure([
        'success',
        'items' => ['data' => [['title']]]
      ]);
  }

  public function testShouldGetAllItemsByTitleSortedInDescendingOrder()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $this->json('GET', '/api/v1/items?sort=title_desc', [], ['Authorization' => $token])
      ->seeStatusCode(200)
      ->seeJsonStructure([
        'success',
        'items' => ['data' => [['title']]]
      ]);
  }

  public function testShouldGetSearchedItems()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $this->json('GET', '/api/v1/items?search=harry potter', [], ['Authorization' => $token])
      ->seeStatusCode(200)
      ->seeJsonStructure([
        'success',
        'items' => ['data' => [['title', 'isbn']]]
      ]);
  }

  public function testShouldFilterItemsByCategoryId()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $this->json('GET', '/api/v1/items?category=1', [], ['Authorization' => $token])
      ->seeStatusCode(200)
      ->seeJsonStructure([
        'success',
        'items' => ['data' => [['title', 'isbn']]]
      ]);
  }

  public function testShouldNotGetItemsWithInvalidSortParams()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $this->json('GET', '/api/v1/items?sort=jghj', [], ['Authorization' => $token])
      ->seeStatusCode(400)
      ->seeJson([
        'success' => false,
        'message' => 'Please enter a valid sort params'
      ]);
  }

  public function testShouldGetItemsWithPagination()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;

    $this->json('GET', '/api/v1/items?page=3&pageSize=2', [], ['Authorization' => $token])
      ->seeStatusCode(200)
      ->seeJson([
        'success' => true,
      ]);
  }

  public function testAddingItemsToStock()
  {
    $items = [
      'itemStateId' => 2,
      'itemCondition' => 'New',
    ];

    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;

    $this->json('POST', '/api/v1/items/1', $items, ['Authorization' => $token])
      ->seeStatusCode(200)
      ->seeJson([
        'success' => true,
        'message' => 'Item Added Successfully'
      ]);
  }

  public function testShouldFailIfItemIdIsInvalid()
  {
    $items = [
      'itemStateId' => 2,
      'itemCondition' => 'New',
    ];

    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;

    $this->json('POST', '/api/v1/items/19', $items, ['Authorization' => $token])
      ->seeStatusCode(400)
      ->seeJson([
        'success' => false,
        'message' => 'Error Occured Adding Items. Ensure the IDs are Valid'
      ]);
  }


  public function testShouldDeleteItemsFromStock()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;

    $this->json('DELETE', '/api/v1/items/1', [], ['Authorization' => $token])
      ->seeStatusCode(204);
  }

  public function testShouldFailToDeleteIfItemNotFound()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;

    $this->json('DELETE', '/api/v1/items/19', [], ['Authorization' => $token])
      ->seeStatusCode(404)
      ->seeJson([
        'success' => false,
        'message' => 'item not found'
      ]);
  }


}
