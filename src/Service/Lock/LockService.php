<?php

declare(strict_types=1);

namespace App\Service\Lock;

use App\Enum\LockStatus;
use App\Service\Storage\FileService;

class LockService
{
    public function __construct(
        private FileService $fileService,
        private string $lockStoragePath
    ) {
    }

    public function getLock(string $name): bool
    {
        $lockStatus = LockStatus::Free;
        if ($this->fileService->isFileExist($this->getFullLockName($name))) {
            $lock = $this->fileService->read($this->getFullLockName($name));
            $lockStatus = LockStatus::from((int)$lock);
        }

        switch ($lockStatus) {
            case LockStatus::Free:
                $this->fileService->write($this->getFullLockName($name), (string)LockStatus::InUse->value);
                return true;
            case LockStatus::InUse:
                return false;
        }
        return false;
    }

    public function releaseLock(string $name): void
    {
        if ($this->fileService->isFileExist($this->getFullLockName($name))) {
            $this->fileService->delete($this->getFullLockName($name));
        }
    }

    private function getFullLockName(string $name): string
    {
        return $this->lockStoragePath . 'lock_'.$name.'txt';
    }
}
