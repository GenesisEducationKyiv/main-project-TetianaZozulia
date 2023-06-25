<?php declare(strict_types=1);

namespace App\Exception;

class NotValidEmail extends \InvalidArgumentException
{
    public function __construct()
    {
        $argumentName = 'email';
        $message = sprintf('Argument `%s` must be a valid email', $argumentName);
        parent::__construct($message);
    }
}
