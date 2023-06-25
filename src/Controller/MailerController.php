<?php declare(strict_types=1);

namespace App\Controller;

use App\Exception\NotValidEmail;
use App\Model\Email;
use App\Model\ResourceModel\SubscriberResource;
use App\Model\SubscriberModel;
use App\Model\Topic;
use App\Service\BusinessCase\SendEmailsByTopicCase;
use App\Service\Subscription\SubscriptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MailerController extends BaseController
{
    public function __construct(
        protected SerializerInterface $serializer,
        private SubscriptionInterface $subscription
    ) {
        parent::__construct($serializer);
    }

    #[Route('/api/subscribe', name: 'subscribe', methods: 'POST')]
    public function subscribe(Request $request): JsonResponse
    {
        try {
            /** @var SubscriberResource $resource */
            $resource = $this->parseBody($request, SubscriberResource::class);
            $subscriber = new SubscriberModel(
                new Email($resource->getEmail()),
                new Topic($resource->getTopic())
            );
            $this->subscription->addSubscriber($subscriber);
        } catch (NotValidEmail | \InvalidArgumentException | BadRequestHttpException $exception) {
            $error = $exception->getMessage();
        }

        return new JsonResponse(
            [
                'status' => isset($error) ? 'failed' : 'succeed',
                'error' => isset($error) ? [$error] : [],
            ],
            isset($error) ? Response::HTTP_BAD_REQUEST : Response::HTTP_OK
        );
    }

    #[Route('/api/sendEmails', name: 'sendEmails', methods: 'POST')]
    public function sendEmails(
        SendEmailsByTopicCase $sendEmailsByTopicCase
    ): JsonResponse {
        $sendEmailsByTopicCase->execute();

        return new JsonResponse([
            'status' => 'succeed',
            'error' => []
        ]);
    }
}
