<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class LockBusyException extends \Exception
{
    public function __construct(string $lockName)
    {
        $message = sprintf('Failed to store the "%s" lock. Try it later.', $lockName);
        parent::__construct($message, Response::HTTP_CONFLICT);
    }
}
