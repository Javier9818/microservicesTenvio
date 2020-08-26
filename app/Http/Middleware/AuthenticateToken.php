<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;

class AuthenticateToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            JWTAuth::parseToken();
            JWTAuth::authenticate();
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return $next($request);
        }catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(["message" => "Unauthorized"], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(["message" => "Unauthorized"], 401);
        }catch (\Illuminate\Database\QueryException $e) {
            return $next($request);
        }

    }
}
