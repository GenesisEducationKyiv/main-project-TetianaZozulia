<?php declare(strict_types=1);

namespace App\Repository;

use App\Model\Email;
use App\Model\Topic;
use App\Serializer\JsonSerializer;
use App\Service\StorageServiceInterface;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Component\Serializer\SerializerInterface;

// TODO class має відповідати лише за роботу зі стореджем
// можна застосувати паттерн стратегія, написати сервіс який буде направляти в який репо дивитись
// SubscriberRepository or SubscriberProcessingRepository
class SubscribeRepository
{
    public function __construct(
        private StorageServiceInterface $fileService,
        private JsonSerializer $serializer,
        private string $filePath,
        private string $processingFilePath
    ) {
    }

    public function read(Topic $topic, bool $isProcessing = false): array
    {
        $content = $this->fileService->read(
            ($isProcessing ? $this->processingFilePath : $this->filePath) . $topic->getFileName()
        );
        return $this->serializer->deserialize($content);
    }

    public function addContent(Topic $topic, Email $email)
    {
        $subscribersListFile = $this->filePath . $topic->getFileName();
        $subscribersList = $this->fileService->isFileExist($subscribersListFile)
            ? $this->fileService->read($subscribersListFile)
            : '';
        $emails = json_decode($subscribersList, true) ?? [];
        array_push($emails, $email->getEmail());
        $emails = array_unique($emails);
        $this->fileService->write(
            $subscribersListFile,
            $this->serializer->serialize($emails)
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
