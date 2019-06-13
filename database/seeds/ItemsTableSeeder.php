<?php
// namespace database\seeds;
use Illuminate\Support\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\ItemStock;

class ItemsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $now = Carbon::now();

    Item::insert([
      [
        "title" => "As you like it",
        "description" => "A play written by the best author",
        "isbn" => "123-42092-2312-9887",
        "authorId" => 1,
        "itemTypeId" => 1,
        "categoryId" => 1,
        "numberInStock" => 3,
        'updated_at' => $now,
        'created_at' => $now,
      ],[
        "title" => "Harry Potter and the prisoner of Askaban",
        "description" => "A book about wizards",
        "isbn" => "623-42092-2312-9887",
        "authorId" => 2,
        "itemTypeId" => 1,
        "categoryId" => 1,
        "numberInStock" => 2,
        'updated_at' => $now,
        'created_at' => $now,
      ],[
        "title" => "New Chemistry for Secondary Schoo",
        "description" => "A book about wizards",
        "isbn" => "923-42092-2312-9887",
        "authorId" => 3,
        "itemTypeId" => 1,
        "categoryId" => 2,
        "numberInStock" => 2,
        'updated_at' => $now,
        'created_at' => $now,
      ],
    ]);

    ItemStock::insert([
      [
        "itemCondition" => "New",
        "itemUniqueCode" => Uuid::uuid(),
        "itemStateId" => 4,
        "itemId" => 1,
        'updated_at' => $now,
        'created_at' => $now,
      ],[
        "itemCondition" => "New",
        "itemUniqueCode" => Uuid::uuid(),
        "itemStateId" => 4,
        "itemId" => 1,
        'updated_at' => $now,
        'created_at' => $now,
      ],[
        "itemCondition" => "New",
        "itemUniqueCode" => Uuid::uuid(),
        "itemStateId" => 4,
        "itemId" => 1,
        'updated_at' => $now,
        'created_at' => $now,
      ],[
        "itemCondition" => "New",
        "itemUniqueCode" => Uuid::uuid(),
        "itemStateId" => 4,
        "itemId" => 2,
        'updated_at' => $now,
        'created_at' => $now,
      ],[
        "itemCondition" => "New",
        "itemUniqueCode" => Uuid::uuid(),
        "itemStateId" => 4,
        "itemId" => 2,
        'updated_at' => $now,
        'created_at' => $now,
      ],[
        "itemCondition" => "New",
        "itemUniqueCode" => Uuid::uuid(),
        "itemStateId" => 4,
        "itemId" => 3,
        'updated_at' => $now,
        'created_at' => $now,
      ], [
        "itemCondition" => "New",
        "itemUniqueCode" => Uuid::uuid(),
        "itemStateId" => 4,
        "itemId" => 3,
        'updated_at' => $now,
        'created_at' => $now,
      ],
    ]);

  }
}
