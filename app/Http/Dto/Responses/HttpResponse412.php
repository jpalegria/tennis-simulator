<?php

namespace App\Http\Dto\Responses;

use App\Http\Dto\HttpResponseDto;
use App\Services\HttpResponse;

class HttpResponse412 extends HttpResponseDto
{
    public function __construct(array|string $message="One or more preconditions on the current resource state is evaluated to false when tested. Used to indicate that an API endpoint has been turned off for this resource.")
    {
        parent::__construct(412, $message);
    }
}
