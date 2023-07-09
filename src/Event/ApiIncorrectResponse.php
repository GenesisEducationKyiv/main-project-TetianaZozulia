<?php

declare(strict_types=1);

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ApiIncorrectResponse extends Event
{
    public const NAME = 'api.incorrect.response';

    public function __construct(
        protected string $exceptionMessage,
        protected string $apiName
    ) {
    }

    public function getExceptionMessage(): string
    {
        return $this->exceptionMessage;
    }

    public function getApiName(): string
    {
        return $this->apiName;
    }
}
