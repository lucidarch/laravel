<?php

/*
 * This file is part of the lucid package.
 *
 * © Vinelab <dev@vinelab.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Foundation;

use Framework\Jobs\Job as FrameworkJob;
use Illuminate\Contracts\Bus\SelfHandling;

/**
 * An abstract Job.
 *
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
abstract class Job extends FrameworkJob implements SelfHandling
{
}
