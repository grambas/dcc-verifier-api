<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    /** @var array */
    public $errors;

    public function __construct(int $statusCode, array $errors, \Throwable $previous = null)
    {
        parent::__construct($statusCode, '', $previous, []);
        $this->errors = $errors;
    }
}
