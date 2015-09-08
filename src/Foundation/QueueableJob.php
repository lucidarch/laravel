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

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * An abstract job that can be managed with a queue.
 *
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class QueueableJob extends Job implements ShouldQueue
{
    use SerializesModels;
    use InteractsWithQueue;
}
