<?php

namespace Modules\ApiLogic\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class ApiQueryParserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /*TODO: Implement Query Parser*/
        /*TODO: Define default query rules*/
        //Todo: Validate query
        //Parse query

        return $next($request);
    }
}
