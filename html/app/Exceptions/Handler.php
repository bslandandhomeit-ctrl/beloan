<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
            return redirect('/');
        elseif($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException)
            return redirect('/');
        elseif(!Auth::check() && $e instanceof \Illuminate\Session\TokenMismatchException){
            if($request->ajax()){
                //return response('Unauthorized.', 401);
            }else{
                return redirect()->route('login');
            }
        }
		return parent::render($request, $e);
	}
}
