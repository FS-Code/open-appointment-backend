<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use Closure;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        
        if (!$request->user) {
            $response = new Response();
            $response->json(['error' => 'Unauthorized'], 401);
            return;
        }

       
        return $next($request);
    }
}
