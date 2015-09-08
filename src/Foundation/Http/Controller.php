<?php

/*
 * This file is part of the lucid package.
 *
 * © Vinelab <dev@vinelab.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Foundation\Http;

use App\Foundation\ServesFeaturesTrait;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * Base controller.
 *
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class Controller extends BaseController
{
    use ValidatesRequests;
    use ServesFeaturesTrait;
}
