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
   * @return void
   */
  public function getAuthors( $page, $pageSize, $author, $sortBy)
  {

    $result = DB::table('authors')->select('name', 'created_at as dateAdded')
      ->when( $author, function ($query, $author) {
        return $query->where( 'name', 'ilike', '%' . $author . '%')
            ->orWhere('id', $author);
      })->when($sortBy, function ($query, $sortBy) {
        return $query->orderBy($sortBy['column'], $sortBy['order']);
      })->paginate($pageSize, ['*'], 'page', $page);

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
    if($id){
      $result = DB::table('items')->select('title', 'description', 'isbn', 'numberInStock','items.created_at as dateAdded', 'authors.name as author')
      ->join('authors', 'items.authorId', '=', 'authors.id')
      ->when( $id, function ($query, $id) {
        return $query->where( 'authors.id', $id);
      })->get();
      return $result;
    }
    return false;
  }
}