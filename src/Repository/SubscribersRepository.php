<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Topic;
use App\Serializer\JsonSerializer;
use App\Service\Storage\StorageServiceInterface;

class SubscribersRepository implements SubscribersRepositoryInterface
{
    public function __construct(
        private StorageServiceInterface $fileService,
        private JsonSerializer $serializer,
        protected string $filePath,
    ) {
    }

    public function read(Topic $topic): array
    {
        $subscribers = $this->fileService->read($this->filePath . $topic->getFileName());
        return $this->serializer->deserialize($subscribers);
    }

    public function write(Topic $topic, array $data): void
    {
        $this->fileService->write(
            $this->filePath . $topic->getFileName(),
            $this->serializer->serialize($data)
        );
    }

    public function delete(Topic $topic): bool
    {
        return $this->fileService->delete(
            $this->filePath . $topic->getFileName()
        );
    }

    public function existFile(Topic $topic): bool
    {
        return $this->fileService->isFileExist(
            $this->filePath . $topic->getFileName()
        );
    }

    public function copyTo(Topic $fromTopic, Topic $toTopic): void
    {
        if ($this->existFile($toTopic)) {
            return ;
        }
        $content = $this->read($fromTopic);
        $this->write($toTopic, $content);
    }
}
