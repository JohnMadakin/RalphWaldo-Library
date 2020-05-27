<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Author;
use Illuminate\Support\Carbon;

class AuthorService
{

  /**
   * post an author to the DB
   * 
   * @param string $author 
   * @return void
   */
  public function addAuthor($author)
  {
    return Author::create([
      'name' => $author
    ])->id;
  }

  /**
   * get authors from the DB
   * 
   * @param string $author 
   * @return result
   */
  public function getAuthors( $page, $pageSize, $author, $sortBy)
  {

    $result = DB::table('authors')->select('id','name', 'created_at as dateAdded')
      ->when( $author, function ($query, $author) {
        return $query->where( 'name', 'ilike', '%' . $author . '%')
            ->orWhere('id', $author);
      })->when($sortBy, function ($query, $sortBy) {
        return $query->orderBy($sortBy['column'], $sortBy['order']);
      })->paginate($pageSize, ['*'], 'page', $page);

      return $result;
  }

  /**
   * get authors from the DB
   * 
   * @return result
   */
  public function getAllAuthors()
  {

    $result = DB::table('authors')->get();

    return $result;
  }


    /**
   * get books by authors from the DB
   * 
   * @param string $author 
   * @return void
   */
  public function getItemsByAuthorId($id)
  {
    $verifyAuthor = Author::find($id);
    if($verifyAuthor){
      $result = DB::table('items')->select('items.id as itemId','title', 'description', 'isbn', 'numberInStock','items.created_at as dateAdded','authors.id as authorId', 'authors.name as author')
      ->join('authors', 'items.authorId', '=', 'authors.id')
      ->when( $id, function ($query, $id) {
        return $query->where( 'authors.id', $id);
      })->get();
      return $result;
    }
    return false;
  }
}
