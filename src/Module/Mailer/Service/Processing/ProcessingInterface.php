<?php

declare(strict_types=1);

namespace App\Module\Mailer\Service\Processing;

use App\Model\Topic;

interface ProcessingInterface
{
    public function createProcessingFile(Topic $topic, array $subscriberList): void;

    public function deleteProcessingFile(Topic $topic): bool;

    public function updateProcessingFile(Topic $topic, array $processedSubscribers): void;

    public function getProcessingData(Topic $topic): array;
}