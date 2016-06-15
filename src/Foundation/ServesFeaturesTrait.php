<?php

namespace App\Foundation;

use Illuminate\Foundation\Bus\DispatchesJobs;

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
