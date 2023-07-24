<?php

declare(strict_types=1);

namespace App\Module\Subscription\Controller;

use App\Controller\BaseController;
use App\Module\Subscription\Model\ResourceModel\SubscriberResource;
use App\Module\Subscription\Service\BusinessCase\AddSubscribersCase;
use App\Serializer\JsonSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SubscriptionController extends BaseController
{
    public function __construct(
        protected SerializerInterface $serializer,
        protected JsonSerializer $jsonSerializer,
        private AddSubscribersCase $addSubscribersCase
    ) {
        parent::__construct($serializer, $jsonSerializer);
    }

    #[Route('/api/subscribe', name: 'subscribe', methods: 'POST')]
    public function subscribe(Request $request): JsonResponse
    {
        /** @var SubscriberResource $resource */
        $resource = $this->parseBody($request, SubscriberResource::class);
        $this->addSubscribersCase->execute($resource);

        return new JsonResponse();
    }
}
