<?php

namespace App\Http\Dto\Responses;

use App\Http\Dto\HttpResponseDto;

class HttpResponse500 extends HttpResponseDto{
    public function __construct(array|string $message="The server encountered an unexpected condition. This endpoint is temporarily having issues.")
    {
        parent::__construct(500, $message);
    }
}