<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

use Tymon\JWTAuth\Token;
use JWTAuth;


class Authenticate
{
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
        $authenticated = true;
        $token = $request->header('x-access-token');
        if($token != null)
        {
            try
            {
               $var= JWTAuth::decode(new Token($token));
                $user= User::findOrFail($var['id']);
                Auth::setUser($user);
            }
            catch(TokenInvalidException $e)
            {
                $authenticated = false;
                dd($e);
            }
        }
        else if(Auth::guard($guard)->guest())
            $authenticated = false;
        if($authenticated)
            return $next($request);
        if($request->ajax() || $request->wantsJson())
            return response()->json(['error' => 'Unauthorized.'], 401);
        return redirect()->guest('login');
    }





}
