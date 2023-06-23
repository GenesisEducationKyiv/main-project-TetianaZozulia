<?php declare(strict_types=1);

namespace App\Service\Subscription;

use App\Model\SubscriberModel;
use App\Service\Storage\StorageServiceInterface;

class Subscription implements SubscriptionInterface
{
    public function __construct(
        private StorageServiceInterface $fileService,
        private string $filePath
    ) {
    }

    public function addSubscriber(SubscriberModel $subscriber): void
    {
        $fileName = $this->filePath . $subscriber->getTopic()->getFileName();
        $subscribersList = $this->fileService->isFileExist($fileName)
            ? $this->fileService->read($fileName)
            : '';
        $emails = json_decode($subscribersList, true) ?? [];
        array_push($emails, $subscriber->getEmail()->getEmail());
        $emails = array_unique($emails);
        $this->fileService->write(
            $this->filePath  . $subscriber->getTopic()->getFileName(),
            json_encode($emails)
        );
    }
}
