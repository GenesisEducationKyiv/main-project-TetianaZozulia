<?php

declare(strict_types=1);

namespace App\Module\Mailer\Service\Processing;

use App\Model\Topic;
use App\Serializer\JsonSerializer;
use App\Storage\FileStorage\FileStorageInterface;

class Processing implements ProcessingInterface
{
    public function __construct(
        private FileStorageInterface $fileStorage,
        private JsonSerializer $serializer,
        private string $path
    ) {
    }

    public function createProcessingFile(Topic $topic, array $subscriberList): void
    {
        if ($this->fileStorage->isFileExist($this->path . $topic->getFileName())) {
            return ;
        }

        $this->rewriteProcessing(
            $topic,
            $this->serializer->serialize($subscriberList)
        );
    }

    public function deleteProcessingFile(Topic $topic): bool
    {
        return $this->fileStorage->delete(
            $this->path . $topic->getFileName()
        );
    }

    public function updateProcessingFile(Topic $topic, array $processedSubscribers): void
    {
        $processingEmails = $this->getProcessingData($topic);
        $emailsDiff = array_diff($processingEmails, $processedSubscribers);
        $this->rewriteProcessing(
            $topic,
            $this->serializer->serialize($emailsDiff)
        );
    }

    public function getProcessingData(Topic $topic): array
    {
        $content = $this->fileStorage->read($this->path . $topic->getFileName());
        return $this->serializer->deserialize($content);
    }

    private function rewriteProcessing(Topic $topic, string $content): void {
        $processingFileName = $this->path . $topic->getFileName();
        $this->fileStorage->write($processingFileName, $content);
    }
}
