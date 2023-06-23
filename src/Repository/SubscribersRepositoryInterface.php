<?php declare(strict_types=1);

namespace App\Repository;

use App\Model\Topic;

interface SubscribersRepositoryInterface
{
    public function read(Topic $topic): string;

    public function write(Topic $topic, string $data): void;

    public function deleteAll(Topic $topic): bool;

    public function existFile(Topic $topic): bool;
}
