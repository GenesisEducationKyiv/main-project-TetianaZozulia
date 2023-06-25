<?php declare(strict_types=1);

namespace App\Service\SubscribersProcessingProvider;

use App\Model\Topic;
use App\Repository\ProcessingSubscribersRepository;
use App\Repository\SubscribersRepository;
use App\Serializer\JsonSerializer;

class SubscribersProcessingProvider
{
    public function __construct(
        private ProcessingSubscribersRepository $processingSubscribersRepository,
        private SubscribersRepository $subscribersRepository,
        private JsonSerializer $serializer
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
        return $this->serializer->deserialize($content);
    }

    public function writeProcessingFile(Topic $topic, array $data): void
    {
        $this->processingSubscribersRepository->write(
            $topic,
            $this->serializer->serialize($data)
        );
    }

    public function deleteProcessingFile(Topic $topic): void
    {
        $this->processingSubscribersRepository->deleteAll($topic);
    }
}
