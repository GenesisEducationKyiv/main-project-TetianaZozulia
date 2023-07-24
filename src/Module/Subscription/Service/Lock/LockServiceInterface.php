<?php

declare(strict_types=1);

namespace App\Module\Subscription\Service\Lock;

interface LockServiceInterface
{
    public function getLock(string $name): bool;

    public function releaseLock(string $name): void;
}