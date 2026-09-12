<?php namespace App\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;
use View;
class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        View::composer('layouts.notifybar', 'App\Http\ViewComposers\NotifyComposer');
        Blade::extend(function($value) {
            return preg_replace('/\@var(.+)/', '<?php ${1}; ?>', $value);
        });

		if (!\App::environment('local')) {
          \URL::forceSchema('https');
		}
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
//		$this->app->bind(
//			'Illuminate\Contracts\Auth\Registrar',
//			'App\Services\Registrar'
//		);
	}

}
