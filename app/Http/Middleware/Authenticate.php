<?php

namespace App\Http\Middleware;
use App\Http\Services\UserService;

use Exception;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class Authenticate
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
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('Authorization');
        if(!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not provided'
            ], 401);
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Provided token is expired.'
            ], 400);
        } catch (Exception $e) {
            echo 'error occured';
            return response()->json([
                'success' => false ,
                'message' => 'An error while decoding token.'
            ], 400);
        }
        $user = UserService::findUserById($credentials->id);
        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not Found'
            ], 400);
        }
        $request->auth = $user;

        return $next($request);
    }
}
