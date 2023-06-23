<?php declare(strict_types=1);

namespace App\Service\SubscribersProcessingProvider;

use App\Model\Topic;
use App\Repository\ProcessingSubscribersRepository;
use App\Repository\SubscribersRepository;

class SubscribersProcessingProvider
{
    public function __construct(
        private ProcessingSubscribersRepository $processingSubscribersRepository,
        private SubscribersRepository $subscribersRepository
    ) {
    }

    public function createProcessingFile(Topic $topic): void
    {
        if ($this->processingSubscribersRepository->existFile($topic)) {
            return ;
        }
        $content = $this->subscribersRepository->read($topic);
        $this->processingSubscribersRepository->write($topic, $content);
    }

    public function readProcessingFile(Topic $topic): array
    {
        $content = $this->processingSubscribersRepository->read($topic);
        return json_decode($content, true);
    }

    public function writeProcessingFile(Topic $topic, array $data): void
    {
        $this->processingSubscribersRepository->write(
            $topic,
            json_encode($data)
        );
    }

    public function deleteProcessingFile(Topic $topic): void
    {
        $this->processingSubscribersRepository->deleteAll($topic);
    }
}
