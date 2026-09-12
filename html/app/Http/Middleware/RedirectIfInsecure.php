<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 8/29/2015
 * Time: 7:09 PM
 */

namespace App\Http\Middleware;

use Closure;

class RedirectIfInsecure {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*
        if (!$request->isSecure()) {
            // redirect to the mathching secure url
            // return redirect()->secure($request->getRequestUri());
            return redirect("https://{$_SERVER['HTTP_HOST']}" . $request->getRequestUri());
        }
        */
        if (!app()->isLocal() && !$request->secure()) {
            // redirect to the mathching secure url
            return redirect()->secure($request->path());
        }

        return $next($request);
    }
}
