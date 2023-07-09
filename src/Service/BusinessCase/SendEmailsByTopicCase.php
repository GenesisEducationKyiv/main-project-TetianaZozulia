<?php declare(strict_types=1);

namespace App\Service\BusinessCase;

use App\Map\Rate as RateMapper;
use App\Model\Mail\MailInterface;
use App\Model\Topic;
use App\Repository\SubscribersRepository;
use App\Serializer\JsonSerializer;
use App\Service\Mailer\BusinessMailerInterface;
use App\Service\Mailer\MailFactoryInterface;

class SendEmailsByTopicCase
{
    public function __construct(
        private GetRateBusinessCase $rateBusinessCase,
        private RateMapper $rateMapper,
        private MailFactoryInterface $mailFactory,
        private BusinessMailerInterface $mailerService,
        private SubscribersRepository $subscribersRepository,
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
            $processingTopic = $topic->createForProcessing();

            $mail = $this->mailFactory->create($topic);
            $mail->setTxt($mailTxt);

            $this->subscribersRepository->copyTo($topic, $processingTopic);
            $emails = $this->subscribersRepository->read($processingTopic);
            $this->sendByChunk($emails, $mail, $processingTopic);
            $this->subscribersRepository->delete($processingTopic);
        }
    }

    private function sendByChunk(array $emails, MailInterface $mail, Topic $processingTopic, int $chunkSize = 2): void
    {
        if (count($emails) > $chunkSize) {
            $this->sendByChunk(array_slice($emails, $chunkSize), $mail, $processingTopic);
            $emails = array_slice($emails, 0, $chunkSize);
        }

        $this->mailerService->batchSend($mail, $emails);
        $processingEmails = $this->subscribersRepository->read($processingTopic);
        $emailsDiff = array_diff($processingEmails, $emails);
        $this->subscribersRepository->write($processingTopic, $emailsDiff);
    }
}
