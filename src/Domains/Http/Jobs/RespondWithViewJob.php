<?php

namespace App\Domains\Http\Jobs;

use Lucid\Foundation\Job;
use Illuminate\Routing\ResponseFactory;

class RespondWithViewJob extends Job
{
    protected $status;
    protected $data;
    protected $headers;
    protected $template;

    public function __construct($template, $data = [], $status = 200, array $headers = [])
    {
        $this->template = $template;
        $this->data = $data;
        $this->status = $status;
        $this->headers = $headers;
    }

    public function handle(ResponseFactory $factory)
    {
        return $factory->view($this->template, $this->data, $this->status, $this->headers);
    }
}