<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserDevice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isMobileDevice()) {
            //add parrams to request
            $request->rows = 10;
        } else {

        }

        return $next($request);
    }

    private function isMobileDevice(): false|int
    {
        return preg_match('/Mobile|Android|Silk|Kindle|BlackBerry|Opera Mini|Opera Mobi/i', $_SERVER['HTTP_USER_AGENT']);
    }
}
