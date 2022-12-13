<?php

namespace App\Http\Dto\Responses;

use App\Http\Dto\HttpResponseDto;
use App\Services\HttpResponse;

class HttpResponse404 extends HttpResponseDto
{
    public function __construct(array|string $message="Check that you are using valid parameters and the correct URI for the endpoint you’re using.")
    {
        parent::__construct(404, $message);
    }
}
