<?php

/*
 * This file is part of the lucid package.
 *
 * © Vinelab <dev@vinelab.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domains\Http\Jobs;

use App\Foundation\Job;
use Illuminate\Routing\ResponseFactory;

/**
 * Run this job to send a JSON response.
 *
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class RespondWithJsonJob extends Job
{
    private $content;
    private $status;
    private $headers;
    private $options;

    public function __construct($content, $status = 200, array $headers = [], $options = 0)
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
        $this->options = $options;
    }

    public function handle(ResponseFactory $response)
    {
        return $response->json($this->content, $this->status, $this->headers, $this->options);
    }
}
