<?php
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BorrowerTest extends TestCase
{

  use DatabaseMigrations;
  protected $user;
  protected $token;

  public function testUserCanBorrowItems()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $header = ['Authorization' => $token];
    $itemCollection = $this->get('/api/v1/items', $header)->response->getContent();

    $itemToBorrow = json_decode($itemCollection)->items->data[0]->itemCode;
    $borrowItem = [
      'libraryCardId' => 1,
      'items' => [
        ['itemUniqueCode' => $itemToBorrow]
      ]
    ];
    $this->post( '/api/v1/borrowers', $borrowItem, $header)
      ->seeStatusCode(201)
          ->seeJsonStructure([
            'success',
            'data',
            'message',
          ]);
  }

  public function testShouldFailIfRequestObjectIsInvalid()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $header = ['Authorization' => $token];
    $itemCollection = $this->get('/api/v1/items', $header)->response->getContent();

    $itemToBorrow = json_decode($itemCollection)->items->data[0]->itemCode;
    $borrowItem = [
      'libraryCardId' => 1,
      'items' => [
        'itemUniqueCode' => $itemToBorrow
      ]
    ];
    $this->post('/api/v1/borrowers', $borrowItem, $header)
      ->seeStatusCode(422);
  }

  public function testUserCantBorrowItemsThatIsNotOnItemsList()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $header = ['Authorization' => $token];
    $borrowItem = [
      'libraryCardId' => 1,
      'items' => [
        [ 'itemUniqueCode' => '19fc197f-cf5e-3ab6-80ca-d494ec220ca4' ]
      ]
    ];
    $this->post('/api/v1/borrowers', $borrowItem, $header)
      ->seeStatusCode(400)
      ->seeJson([
        'success' => false,
        'message' => 'One or More Items are not Available',
      ]);
  }


  public function testUserCantBorrowItemsToUsersNotRegistered()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $header = ['Authorization' => $token];
    $itemCollection = $this->get('/api/v1/items', $header)->response->getContent();
    $itemToBorrow = json_decode($itemCollection)->items->data[0]->itemCode;

    $borrowItem = [
      'libraryCardId' => 50,
      'items' => [
        ['itemUniqueCode' => $itemToBorrow]
      ]
    ];
    $this->post('/api/v1/borrowers', $borrowItem, $header)
      ->seeStatusCode(400)
      ->seeJson([
        'success' => false,
        'message' => 'Error borrowing items. Ensure the IDs or ItemUniqueCode(s) are Valid',
      ]);
  }

  public function testBorrowRequestShouldNotHaveDuplicateItemCodes()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $header = ['Authorization' => $token];
    $itemCollection = $this->get('/api/v1/items', $header)->response->getContent();
    $itemToBorrow = json_decode($itemCollection)->items->data[0]->itemCode;

    $borrowItem = [
      'libraryCardId' => 1,
      'items' => [
        ['itemUniqueCode' => $itemToBorrow],
        ['itemUniqueCode' => $itemToBorrow]
      ]
    ];
    $this->post('/api/v1/borrowers', $borrowItem, $header)
      ->seeStatusCode(400)
      ->seeJson([
        'success' => false,
        'message' => 'Duplicate item contents',
      ]);
  }

  public function testShouldNotBorrowCopiesOfTheSameItem()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $header = ['Authorization' => $token];
    $itemCollection = $this->get('/api/v1/items', $header)->response->getContent();
    $firstCopy = json_decode($itemCollection)->items->data[0]->itemCode;
    $secondCopy = json_decode($itemCollection)->items->data[1]->itemCode;

    $borrowItem = [
      'libraryCardId' => 1,
      'items' => [
        ['itemUniqueCode' => $firstCopy],
        ['itemUniqueCode' => $secondCopy]
      ]
    ];
    $this->post('/api/v1/borrowers', $borrowItem, $header)
      ->seeStatusCode(400)
      ->seeJson([
        'success' => false,
        'message' => 'You can not borrow copies of the same Item',
      ]);
  }


  public function testShouldGetItemsBorrowedByUser()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $header = ['Authorization' => $token];
    $itemCollection = $this->get('/api/v1/items', $header)->response->getContent();

    $itemToBorrow = json_decode($itemCollection)->items->data[0]->itemCode;
    $borrowItem = [
      'libraryCardId' => 1,
      'items' => [
        ['itemUniqueCode' => $itemToBorrow]
      ]
    ];
    $this->post('/api/v1/borrowers', $borrowItem, $header);
    $this->get( '/api/v1/users/1/items', $header)
      ->seeStatusCode(200)
      ->seeJsonStructure([
        'Success',
        'Items'
      ]);
  }

  public function testShouldNotGetItemsBorrowedIfUserNotFound()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $header = ['Authorization' => $token];
    $itemCollection = $this->get('/api/v1/items', $header)->response->getContent();

    $itemToBorrow = json_decode($itemCollection)->items->data[0]->itemCode;
    $borrowItem = [
      'libraryCardId' => 1,
      'items' => [
        ['itemUniqueCode' => $itemToBorrow]
      ]
    ];
    $this->post('/api/v1/borrowers', $borrowItem, $header);
    $this->get('/api/v1/users/3/items', $header)
      ->seeStatusCode(404)
      ->seeJsonStructure([
        'Success',
        'Items'
      ]);
  }

  public function testShouldReturnItemsBorrowedByUser()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $header = ['Authorization' => $token];
    $itemCollection = $this->get('/api/v1/items', $header)->response->getContent();

    $itemToBorrow = json_decode($itemCollection)->items->data[0]->itemCode;
    $borrowItem = [
      'libraryCardId' => 1,
      'items' => [
        ['itemUniqueCode' => $itemToBorrow]
      ]
    ];
    $returnItem = [
      'items' => [
        [ 'itemUniqueCode' => $itemToBorrow,
        'returnStateId' => 4,
        'finesAccrued' => 0 ]
      ]
    ];
    $this->post('/api/v1/borrowers', $borrowItem, $header);
    $this->post( '/api/v1/returns', $returnItem, $header)
      ->seeStatusCode(200)
      ->seeJson([
      'success' => true,
      'message' => 'Items returned successfully'
      ]);
  }

  public function testShouldFailIfItemCodeIsInvalid()
  {
    $response = $this->call('POST', '/api/v1/login', [
      'userName' => 'test1',
      'password' => 'password@1',
    ]);
    $token = json_decode($response->getContent())->token;
    $header = ['Authorization' => $token];
    $itemCollection = $this->get('/api/v1/items', $header)->response->getContent();

    $itemToBorrow = json_decode($itemCollection)->items->data[0]->itemCode;
    $borrowItem = [
      'libraryCardId' => 1,
      'items' => [
        ['itemUniqueCode' => $itemToBorrow]
      ]
    ];
    $returnItem = [
      'items' => [
        [
          'itemUniqueCode' => '19fc197f-cf5e-3ab6-80ca-d494ec220ca4',
          'returnStateId' => 4,
          'finesAccrued' => 0
        ]
      ]
    ];
    $this->post('/api/v1/borrowers', $borrowItem, $header);
    $this->post('/api/v1/returns', $returnItem, $header)
      ->seeStatusCode(400)
      ->seeJson([
        'success' => false,
        'message' => 'Invalid Borrowed item code. Ensure the code is correct and state is valid'
      ]);
  }

}
