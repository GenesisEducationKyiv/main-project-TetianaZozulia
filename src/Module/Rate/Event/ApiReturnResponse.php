<?php

declare(strict_types=1);

namespace App\Module\Rate\Event;

use App\Module\Rate\Model\RateInterface;
use Symfony\Contracts\EventDispatcher\Event;

class ApiReturnResponse extends Event
{
    public const NAME = 'api.return.response';

    public function __construct(
        protected RateInterface $rate,
        protected string $apiName
    ) {
    }

    public function getRate(): RateInterface
    {
        return $this->rate;
    }

    public function getApiName(): string
    {
        return $this->apiName;
    }
}
