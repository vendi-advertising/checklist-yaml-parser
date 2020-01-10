<?php

namespace App\Exceptions;

use Exception;

final class ArrayMissingRequiredKeyException extends Exception
{
    public function __construct(string $key)
    {
        parent::__construct(sprintf('An array key named %1$s was expected but not found', $key));
    }
}
