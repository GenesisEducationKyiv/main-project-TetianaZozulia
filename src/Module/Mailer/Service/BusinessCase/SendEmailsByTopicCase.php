<?php

declare(strict_types=1);

namespace App\Module\Mailer\Service\BusinessCase;

use App\Module\Mailer\Service\Processing\ProcessingInterface;
use App\Module\Rate\Map\Rate as RateMapper;
use App\Module\Mailer\Model\Mail\MailInterface;
use App\Model\Topic;
use App\Module\Rate\Service\BusinessCase\GetRateBusinessCase;
use App\Module\Subscription\Service\Subscription\SubscriptionInterface;
use App\Serializer\JsonSerializer;
use App\Module\Mailer\Service\Mailer\BusinessMailerInterface;
use App\Module\Mailer\Service\Mailer\MailFactoryInterface;

class SendEmailsByTopicCase
{
    public function __construct(
        private GetRateBusinessCase $rateBusinessCase,
        private RateMapper $rateMapper,
        private MailFactoryInterface $mailFactory,
        private BusinessMailerInterface $mailerService,
        private SubscriptionInterface $subscription,
        private ProcessingInterface $processingService,
        private JsonSerializer $serializer
    ) {
    }

    public function execute(): void
    {
        $rate = $this->rateBusinessCase->execute();
        $mailTxt = $this->serializer->serialize($this->rateMapper->toArray($rate));
        unset($rate);

        foreach (array_keys(Topic::AVAILABLE_TOPICS) as $topicName) {
            $topic = new Topic($topicName);

            $mail = $this->mailFactory->create($topic);
            $mail->setTxt($mailTxt);

            $subscribersList = $this->subscription->getSubscribers($topic);
            $this->processingService->createProcessingFile($topic, $subscribersList);
            $emails = $this->processingService->getProcessingData($topic);
            $this->sendByChunk($emails, $mail, $topic);
            $this->processingService->deleteProcessingFile($topic);
        }
    }

    private function sendByChunk(array $emails, MailInterface $mail, Topic $topic, int $chunkSize = 2): void
    {
        if (count($emails) > $chunkSize) {
            $this->sendByChunk(array_slice($emails, $chunkSize), $mail, $topic);
            $emails = array_slice($emails, 0, $chunkSize);
        }

        $this->mailerService->batchSend($mail, $emails);
        $this->processingService->updateProcessingFile($topic, $emails);
    }
}
