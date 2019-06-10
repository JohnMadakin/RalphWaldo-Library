<?php

namespace App\Http\Middleware;

use App\Http\Services\UserService;

use Exception;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use App\Http\Helpers\ControllerHelpers;

class AuthoriseToBorrow
{

  /**
   * Handle an incoming request and Authorizes the user.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @param  string|null  $guard
   * @return mixed
   */
  public function handle($request, Closure $next, $guard = null)
  {
    $userId = $request->auth->id;
    $borrowerId = $request->input('libraryCardId');
    if($userId == $borrowerId){
      return response()->json([
        'success' => false,
        'message' => 'Not Authorise to Borrow Books'
      ], 403);
    }
    return $next($request);
  }
}
