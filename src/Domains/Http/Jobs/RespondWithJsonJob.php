<?php

namespace App\Domains\Http\Jobs;

use App\Foundation\Job;
use Illuminate\Routing\ResponseFactory;

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
