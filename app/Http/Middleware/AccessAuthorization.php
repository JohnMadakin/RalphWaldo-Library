<?php

namespace App\Http\Middleware;

use App\Http\Services\UserService;

use Exception;
use Closure;
use App\Http\Helpers\ControllerHelpers;

class AccessAuthorization
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
    $userRole = ControllerHelpers::checkUserRole($userId);
    if ($userRole == false || $userRole == 'User') {
      return response()->json([
        'success' => false,
        'message' => 'Not Authorise to access this route'
      ], 403);
    }
    return $next($request);
  }
}
