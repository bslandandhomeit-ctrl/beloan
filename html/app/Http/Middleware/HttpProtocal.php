<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;

class HttpProtocal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next)
    {
    	if(env('APP_ENV', 'local') !='local'){
    		\URL::forceSchema('http');
    	}
        return $next($request);
	  }
}
