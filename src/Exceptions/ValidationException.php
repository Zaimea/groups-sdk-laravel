<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Exceptions;

class ValidationException extends ApiException
{
    protected array $errors;

    public function __construct(
        string $message = '',
        array $errors = [],
        int $code = 422,
        ?\Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}