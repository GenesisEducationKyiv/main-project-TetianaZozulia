<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class DuplicateEmailException extends \InvalidArgumentException
{
    public function __construct(string $email)
    {
        $message = sprintf('Email "%s" already exists', $email);
        parent::__construct($message, Response::HTTP_CONFLICT);
    }
}
