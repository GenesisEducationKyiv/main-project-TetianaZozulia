<?php declare(strict_types=1);

namespace App\Service\BusinessCase;

use App\Map\Rate as RateMapper;
use App\Model\Topic;
use App\Service\Mailer\BusinessMailerInterface;
use App\Service\Mailer\MailFactory;
use App\Service\SubscribersProcessingProvider\SubscribersProcessingProvider;

class SendEmailsByTopicCase
{
    public function __construct(
        private GetRateBusinessCase $rateBusinessCase,
        private RateMapper $rateMapper,
        private MailFactory $mailFactory,
        private BusinessMailerInterface $mailerService,
        private SubscribersProcessingProvider $processingProvider,
    ) {
    }

    public function execute(): void
    {
        $rate = $this->rateBusinessCase->execute();
        $mailTxt = json_encode($this->rateMapper->toArray($rate));
        unset($rate);

        foreach (array_keys(Topic::AVAILABLE_TOPICS) as $topicName) {
            $mail = $this->mailFactory->create(new Topic($topicName));
            $mail->setTxt($mailTxt);

            $this->processingProvider->createProcessingFile(new Topic($topicName));
            $emails = $this->processingProvider->readProcessingFile(new Topic($topicName));
            $chunks = array_chunk($emails, 2);

            foreach ($chunks as $chunk) {
                $this->mailerService->batchSend($mail, $chunk);
                $emails = $emailsDiff = array_diff($emails, $chunk);
                $this->processingProvider->writeProcessingFile(
                    new Topic($topicName),
                    $emailsDiff
                );
            }

            $this->processingProvider->deleteProcessingFile(new Topic($topicName));
        }
    }
}
