<?php

namespace App\Foundation\Providers;

use Illuminate\Routing\Router;
use Caffeinated\Modules\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This is the service directory name.
     *
     * @var string
     */
    protected $serviceName = '';

    /**
     * Define the routes for the module.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function map(Router $router)
    {
        $namespace = config('modules.namespace').$this->serviceName.'\Http\Controllers';

        $router->group(['namespace' => $namespace], function ($router) {
            $routesFile = config('modules.path').'/'.$this->serviceName.'/Http/routes.php';

            if (file_exists($routesFile)) {
                require $routesFile;
            }
        });
    }
}
