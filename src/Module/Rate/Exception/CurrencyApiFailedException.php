<?php

declare(strict_types=1);

namespace App\Module\Rate\Exception;

class CurrencyApiFailedException extends \Exception
{
    public function __construct(string $apiName, int $code)
    {
        $message = sprintf('Api `%s` execute failed.', $apiName);
        parent::__construct($message);
    }
}
