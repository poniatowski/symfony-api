<?php declare(strict_types = 1);

namespace App\Exception;

use Exception;
use JsonSchema\Exception\RuntimeException;

class JsonValidationException extends RuntimeException
{
    protected ?array $errors;

    public function __construct(array $message = null, $code = 0, Exception $previous = null)
    {
        parent::__construct(json_encode($message), $code, $previous);

        $this->errors = $message;
    }

    public function getErrorMessage()
    {
        return $this->errors;
    }
}