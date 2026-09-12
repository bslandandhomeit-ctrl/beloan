<?php
/**
 * Created by PhpStorm.
 * User: SOPHEAK
 * Date: 7/16/2015
 * Time: 4:54 PM
 */

namespace App\Http\Middleware;


use Closure;

class XSSProtection {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->ajax()){
            if (!in_array(strtolower($request->method()), ['put', 'post'])) {
                return $next($request);
            }
        }
        $input = $request->all();
        array_walk_recursive($input, function(&$input) {
            $input = strip_tags($input);
        });

        $request->merge($input);

        return $next($request);
    }
} 