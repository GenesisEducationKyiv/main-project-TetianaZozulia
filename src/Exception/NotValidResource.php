<?php

declare(strict_types=1);

namespace App\Exception;

class NotValidResource extends \InvalidArgumentException
{
    public function __construct(string $message)
    {
        $argumentName = 'resource';
        $message .= sprintf('Argument `%s` must be a valid', $argumentName);
        parent::__construct($message);
    }
}
