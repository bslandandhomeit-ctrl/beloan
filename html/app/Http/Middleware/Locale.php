<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 6/08/2015
 * Time: 8:42 AM
 */

namespace App\Http\Middleware;


use Closure;

class Locale {


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	require_once(public_path('global.php')); //chuch add global constant
        $locale = session('locale', config('app.locale'));
        app()->setLocale($locale);
        return $next($request);
    }
}
