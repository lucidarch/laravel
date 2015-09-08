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

use ReflectionClass;
use Illuminate\Http\Request;
use App\Domains\Qmesueue\DefaultQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * An Abstract Feature.
 *
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
abstract class Feature implements SelfHandling
{
    use DispatchesJobs;

    /**
     * beautifier function to be called instead of the
     * laravel function dispatchFromArray.
     * When the $arguments is an instance of Request
     * it will call dispatchFrom instead.
     *
     * @param string                         $job
     * @param array|\Illuminate\Http\Request $arguments
     *
     * @return mixed
     */
    public function run($job, $arguments, $extra = [])
    {
        if ($arguments instanceof Request) {
            $result = $this->dispatchFrom($job, $arguments, $extra);
        } else {
            $result = $this->dispatchFromArray($job, $arguments);
        }

        return $result;
    }

    /**
     * Run the given job in the given queue.
     *
     * @param string     $job
     * @param array      $arguments
     * @param Queue|null $queue
     *
     * @return mixed
     */
    public function runInQueue($job, $arguments, Queue $queue = null)
    {
        if (!$queue) {
            $queue = DefaultQueue::class;
        }

        // instantiate and queue the job
        $reflection = new ReflectionClass($job);
        $jobInstance = $reflection->newInstanceArgs($arguments);
        $jobInstance->onQueue((string) $queue);

        return $this->dispatch($jobInstance);
    }
}
