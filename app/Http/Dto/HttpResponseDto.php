<?php

namespace App\Http\Dto;

/**
 * Data Transfer Object for Http Response
 */
abstract class HttpResponseDto
{
    private int $code;
    private array|string $message;

    public function __construct(int $code, string $message)
    {
        $this->setCode($code);
        $this->setMessage($message);
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return self
     */
    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): array|string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return self
     */

    public function setMessage(array|string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Parse object and return a array to use like a response http.
     * @return array
     */
    public function parse()
    {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage()
        ];
    }
}
