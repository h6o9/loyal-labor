<?php

namespace Instamojo\Exceptions;

class InvalidRequestException extends InstamojoException
{
    /**
     * @param $errorMessage
     */
    public function __construct($errorMessage)
    {
        parent::__construct(null, null, $errorMessage);
    }
}
