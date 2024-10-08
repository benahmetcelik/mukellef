<?php

namespace App\Http\Responses;

class ServiceResponse
{


    /**
     * @var string
     */
    private string $message;

    /**
     * @var int
     */
    private int $statusCode;

    /**
     * @var mixed
     */
    private mixed $data;

    /**
     * @var bool
     */
    private bool $isSuccess;

    /**
     * @param bool $isSuccess
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     */
    public function __construct(bool   $isSuccess,
                                string $message,
                                mixed  $data,
                                int    $statusCode)
    {
        $this->isSuccess = $isSuccess;
        $this->message = $message;
        $this->data = $data;
        $this->statusCode = $statusCode;
    }

    /**
     * @return bool
     */
    public function getIsSuccess(): bool
    {
        return $this->isSuccess;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getData():mixed
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }


}
