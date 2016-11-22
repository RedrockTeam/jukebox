<?php

namespace App\Modules\Jukebox\Providers;

use Illuminate\Routing\Router;
use Caffeinated\Modules\Providers\RouteServiceProvider as ServiceProvider;

use App\Modules\Jukebox\Http\Middleware\Authenticate;
use App\Modules\Jukebox\Http\Middleware\WeixinAuthenticate;

class RouteServiceProvider extends ServiceProvider
{
	/**
	 * The controller namespace for the module.
	 *
	 * @var string|null
	 */
	protected $namespace = 'App\Modules\Jukebox\Http\Controllers';

	/**
	 * Define your module's route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
		parent::boot($router);

        $router->middleware('weixin.auth', WeixinAuthenticate::class);
		$router->middleware('jukebox.auth', Authenticate::class);
	}

	/**
	 * Define the routes for the module.
	 *
	 * @param  \Illuminate\Routing\Router $router
	 * @return void
	 */
	public function map(Router $router)
	{
		$router->group([
			'namespace'  => $this->namespace,
			'middleware' => ['web']
		], function($router) {
			require (config('modules.path').'/Jukebox/Http/routes.php');
		});
	}
}
