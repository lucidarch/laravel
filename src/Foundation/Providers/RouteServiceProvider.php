<?php

/*
 * This file is part of the lucid package.
 *
 * © Vinelab <dev@vinelab.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Foundation\Providers;

use App\Foundation\RouteServiceProvider as ServiceProvider;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
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
