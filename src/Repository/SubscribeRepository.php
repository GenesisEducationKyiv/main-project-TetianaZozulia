<?php declare(strict_types=1);

namespace App\Repository;

use App\Model\Email;
use App\Model\Topic;
use App\Service\StorageServiceInterface;

// TODO class має відповідати лише за роботу зі стореджем
// можна застосувати паттерн стратегія, написати сервіс який буде направляти в який репо дивитись
// SubscriberRepository or SubscriberProcessingRepository
class SubscribeRepository
{
    public function __construct(
        private StorageServiceInterface $fileService,
        private string $filePath,
        private string $processingFilePath
    ) {
    }

    public function read(Topic $topic, bool $isProcessing = false): array
    {
        $json = $this->fileService->read(
            ($isProcessing ? $this->processingFilePath : $this->filePath) . $topic->getFileName()
        );
        return json_decode($json, true);
    }

    public function addContent(Topic $topic, Email $email)
    {
        $emails = $this->read($topic);
        array_push($emails, $email->getEmail());
        $emails = array_unique($emails);
        $this->fileService->write(
            $this->filePath  . $topic->getFileName(),
            json_encode($emails)
        );
    }

    public function write(Topic $topic, string $data, bool $isProcessing = false): void
    {
        $this->fileService->write(
            ($isProcessing ? $this->processingFilePath : $this->filePath)  . $topic->getFileName(),
            $data
        );
    }

    public function createFileToProcessing(Topic $topic): bool
    {
        if ($this->existProcessingFile($topic)) {
            return true;
        }

        return $this->fileService->copy(
            $this->filePath . $topic->getFileName(),
            $this->processingFilePath . $topic->getFileName()
        );
    }

    public function deleteProcessingFile(Topic $topic): bool
    {
        return $this->fileService->delete(
            $this->processingFilePath . $topic->getFileName()
        );
    }

    public function existProcessingFile(Topic $topic): bool
    {
        return $this->fileService->isFileExist(
            $this->processingFilePath . $topic->getFileName()
        );
    }
}
