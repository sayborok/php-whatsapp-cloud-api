<?php

namespace WhatsApp\Exceptions;

use Exception;

/**
 * Custom Exception for WhatsApp API Errors
 */
class WhatsAppException extends Exception
{
    private $errorData;

    public function __construct(string $message, int $code = 0, array $errorData = [])
    {
        parent::__construct($message, $code);
        $this->errorData = $errorData;
    }

    public function getErrorData(): array
    {
        return $this->errorData;
    }
}
