<?php

namespace App\Http\Dto\Responses;

use App\Http\Dto\HttpResponseDto;

class HttpResponse422 extends HttpResponseDto
{
    public function __construct(array|string $message="Check that the data you are sending in your request is valid.")
    {
        parent::__construct(422, $message);
    }
}
