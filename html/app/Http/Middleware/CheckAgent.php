<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 18/09/2015
 * Time: 1:46 PM
 */

namespace App\Http\Middleware;

use Closure;
use Agent;

class CheckAgent {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       // if(!Agent::isDesktop()){
       //     return redirect()->away('http://192.168.111.16');
       // }
       return $next($request);
    }
}
