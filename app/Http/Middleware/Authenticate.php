<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Firebase\JWT\JWT;
use App\Models\User;

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
        $token = $request->bearerToken();

        try {
            $data = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Il token fornito non è valido'], 401);
        }

        $user = User::find($data->sub);

        if (!$user) {
            // log della richiesta
            return response()->json(['error' => 'Il token fornito non è valido'], 401);
        }

        $request->auth = $user;

        return $next($request);
    }
}
