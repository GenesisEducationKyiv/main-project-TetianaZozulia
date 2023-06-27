<?php declare(strict_types=1);

namespace App\Service\BusinessCase;

use App\Map\Rate as RateMapper;
use App\Model\Mail\MailInterface;
use App\Model\Topic;
use App\Serializer\JsonSerializer;
use App\Service\Mailer\BusinessMailerInterface;
use App\Service\Mailer\MailFactory;
use App\Service\Mailer\MailFactoryInterface;
use App\Service\SubscribersProcessingProvider\SubscribersProcessingProvider;

class SendEmailsByTopicCase
{
    public function __construct(
        private GetRateBusinessCase $rateBusinessCase,
        private RateMapper $rateMapper,
        private MailFactoryInterface $mailFactory,
        private BusinessMailerInterface $mailerService,
        private SubscribersProcessingProvider $processingProvider,
        private JsonSerializer $serializer
    ) {
    }

    public function execute(): void
    {
        $rate = $this->rateBusinessCase->execute();
        $mailTxt = $this->serializer->serialize($this->rateMapper->toArray($rate));
        unset($rate);

        foreach (array_keys(Topic::AVAILABLE_TOPICS) as $topicName) {
            $mail = $this->mailFactory->create(new Topic($topicName));
            $mail->setTxt($mailTxt);

            $this->processingProvider->createProcessingFile(new Topic($topicName));
            $emails = $this->processingProvider->readProcessingFile(new Topic($topicName));
            $this->sendByChunk($emails, $mail, new Topic($topicName));
            $this->processingProvider->deleteProcessingFile(new Topic($topicName));
        }
    }

    private function sendByChunk(array $emails, MailInterface $mail, Topic $topic, int $chunkSize = 2): void
    {
        if (count($emails) > $chunkSize) {
            $this->sendByChunk(array_slice($emails, $chunkSize), $mail, $topic);
            $emails = array_slice($emails, 0, $chunkSize);
        }

        $this->mailerService->batchSend($mail, $emails);
        $processingEmails = $this->processingProvider->readProcessingFile($topic);
        $emailsDiff = array_diff($processingEmails, $emails);
        $this->processingProvider->writeProcessingFile($topic, $emailsDiff);
    }
}
