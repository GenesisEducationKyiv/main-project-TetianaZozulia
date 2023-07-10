<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\ResourceModel\SubscriberResource;
use App\Service\BusinessCase\AddSubscribersCase;
use App\Service\BusinessCase\SendEmailsByTopicCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MailerController extends BaseController
{
    public function __construct(
        protected SerializerInterface $serializer,
        private AddSubscribersCase $addSubscribersCase
    ) {
        parent::__construct($serializer);
    }

    #[Route('/api/subscribe', name: 'subscribe', methods: 'POST')]
    public function subscribe(Request $request): JsonResponse
    {
        /** @var SubscriberResource $resource */
        $resource = $this->parseBody($request, SubscriberResource::class);
        $this->addSubscribersCase->execute($resource);

        return new JsonResponse();
    }

    #[Route('/api/sendEmails', name: 'sendEmails', methods: 'POST')]
    public function sendEmails(
        SendEmailsByTopicCase $sendEmailsByTopicCase
    ): JsonResponse {
        $sendEmailsByTopicCase->execute();

        return new JsonResponse();
    }
}
