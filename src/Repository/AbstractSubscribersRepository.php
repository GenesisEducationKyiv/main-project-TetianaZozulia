<?php declare(strict_types=1);

namespace App\Repository;

use App\Model\Topic;
use App\Service\Storage\StorageServiceInterface;

class AbstractSubscribersRepository implements SubscribersRepositoryInterface
{
    public function __construct(
        private StorageServiceInterface $fileService,
        protected string $filePath,
    ) {
    }

    public function read(Topic $topic): string
    {
        return $this->fileService->read($this->filePath . $topic->getFileName());
    }

    public function write(Topic $topic, string $data): void
    {
        $this->fileService->write(
            $this->filePath . $topic->getFileName(),
            $data
        );
    }

    public function deleteAll(Topic $topic): bool
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
}
