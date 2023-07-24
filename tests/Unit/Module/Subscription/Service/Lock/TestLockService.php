<?php
declare(strict_types=1);

namespace App\Tests\Unit\Module\Subscription\Service\Lock;

use App\Module\Subscription\Service\Lock\LockServiceInterface;

class TestLockService implements LockServiceInterface
{
    public function __construct(public bool $expectedLockStatus)
    {
    }

    public function getLock(string $name): bool
    {
        return $this->expectedLockStatus;
    }

    public function releaseLock(string $name): void
    {
        return;
    }
}
