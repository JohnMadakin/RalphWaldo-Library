<?php

namespace App\Http\Middleware;

use App\Http\Services\UserService;

use Exception;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class Authorization
{
  /**
   * The authentication guard factory instance.
   *
   * @var \Illuminate\Contracts\Auth\Factory
   */
  protected $auth;

  /**
   * Create a new middleware instance.
   *
   * @param  \Illuminate\Contracts\Auth\Factory  $auth
   * @return void
   */
  public function __construct(Auth $auth)
  {
    $this->auth = $auth;
  }

  /**
   * Handle an incoming request and Authorizes the user.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @param  string|null  $guard
   * @return mixed
   */
  public function handleAuthorization($request, Closure $next, $guard = null)
  {
    $userId = $request->auth->id;
    $getUserRole = UserService::findUserRoleById($userId);
    // var_dump($getUserRole);
  }
}
