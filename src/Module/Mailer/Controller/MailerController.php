<?php

declare(strict_types=1);

namespace App\Module\Mailer\Controller;

use App\Controller\BaseController;
use App\Module\Mailer\Service\BusinessCase\SendEmailsByTopicCase;
use App\Serializer\JsonSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MailerController extends BaseController
{
    public function __construct(
        protected SerializerInterface $serializer,
        protected JsonSerializer $jsonSerializer,
    ) {
        parent::__construct($serializer, $jsonSerializer);
    }

    #[Route('/api/sendEmails', name: 'sendEmails', methods: 'POST')]
    public function sendEmails(
        SendEmailsByTopicCase $sendEmailsByTopicCase
    ): JsonResponse {
        $sendEmailsByTopicCase->execute();

        return new JsonResponse();
    }
}
