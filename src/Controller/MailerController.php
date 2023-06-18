<?php declare(strict_types=1);

namespace App\Controller;

use App\Map\Rate;
use App\Model\Email;
use App\Model\Mail\CurrencyMail;
use App\Model\Topic;
use App\Repository\RateRepository;
use App\Repository\SubscribeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    public function __construct(private SubscribeRepository $repository)
    {
    }

    #[Route('/subscribe', name: 'subscribe', methods: 'POST')]
    public function subscribe(Request $request): JsonResponse
    {
        $error = [];
        try {
            $content = json_decode($request->getContent(), true);
            $email = new Email($content['email']);
            $topic = new Topic($content['topic'] ?? 'currency');
        } catch (\InvalidArgumentException $exception) {
            $error[] = $exception->getMessage();
        }
        $this->repository->addContent($topic, $email);

        return new JsonResponse([
            'status' => count($error) > 0 ? 'failed' : 'succeed',
            'error' => $error
        ]);
    }

    #[Route('/sendEmails', name: 'sendEmails', methods: 'POST')]
    public function sendEmails(
        RateRepository $rateRepository,
        Rate $rateMapper,
        MailerInterface $mailer
    ): JsonResponse {
        $error = [];
        //TODO if we divide subscribers by topic than we have to divide letter content by topic
        //TODO and we have to create template for letter and fill it with this txt
        $rate = $rateRepository->read();
        $txt = json_encode($rateMapper->toArray($rate));
        unset($rate);
        foreach (array_keys(Topic::AVAILABLE_TOPICS) as $topicName) {
            $this->repository->createFileToProcessing(new Topic($topicName));
            $mail = new CurrencyMail(
                new Email('test@test.test'),
                $txt,
                ''
            );
            $emails = $this->repository->read(new Topic($topicName), true);
            $chunks = array_chunk($emails, 2);

            foreach ($chunks as $chunk) {
                foreach ($chunk as $email) {
                    try {
                        $mail->setTo(new Email($email));
                        $letter = (new \Symfony\Component\Mime\Email())
                            ->from($mail->getFrom()->getEmail())
                            ->to($mail->getTo()->getEmail())
                            ->subject($mail->getSubject())
                            ->text($mail->getTxt())
                            ->html($mail->getHtml());

                        $mailer->send($letter);
                    } catch (\Throwable $exception) {
                        $error['sendEmail'][$email] = $exception->getMessage();
                    }
                }
                unset($mail, $letter);
                //rewrite processing file
                //if process failed new request start work from place where it stopped
                $emails = $emailsDiff = array_diff($emails, $chunk);
                $this->repository->write(
                    new Topic($topicName),
                    json_encode($emailsDiff),
                    true
                );
            }

            $this->repository->deleteProcessingFile(new Topic($topicName));
        }

        return new JsonResponse([
            'status' => count($error) > 0 ? 'failed' : 'succeed',
            'error' => $error
        ]);
    }
}
