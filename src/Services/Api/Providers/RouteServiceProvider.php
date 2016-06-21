<?php
namespace App\Services\Api\Providers;

use Illuminate\Routing\Router;
use App\Foundation\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Read the routes from the "routes.php" file of this service
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function map(Router $router)
    {
        $namespace = 'App\Services\Api\Http\Controllers';
        $routesPath = __DIR__.'/../Http/routes.php';

        $this->loadRoutesFile($router, $namespace, $routesPath);
    }
}
