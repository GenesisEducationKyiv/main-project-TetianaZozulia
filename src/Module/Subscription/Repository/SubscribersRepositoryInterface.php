<?php

declare(strict_types=1);

namespace App\Module\Subscription\Repository;

use App\Model\Topic;

interface SubscribersRepositoryInterface
{
    public function read(Topic $topic): array;

    public function write(Topic $topic, array $data): void;

    public function delete(Topic $topic): bool;

    public function existFile(Topic $topic): bool;

    public function copyTo(Topic $fromTopic, Topic $toTopic): void;
}
