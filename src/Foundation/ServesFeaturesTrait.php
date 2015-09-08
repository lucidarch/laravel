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

use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
trait ServesFeaturesTrait
{
    use DispatchesJobs;

    /**
     * Serve the given feature with the given arguments.
     *
     * @param \App\Foundation\AbstractFeature $feature
     * @param array                                 $arguments
     *
     * @return mixed
     */
    public function serve($feature, $arguments = [])
    {
        return $this->dispatchFromArray($feature, $arguments);
    }
}
