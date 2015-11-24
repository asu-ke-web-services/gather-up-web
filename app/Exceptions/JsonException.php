<?php

namespace GatherUp\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class JsonException extends HttpException
{
    public function __construct($statusCode, $message = null, \Exception $previous = null, array $headers = array(), $code = 0)
    {
        parent::__construct($statusCode, $message, $previous);
    }
}