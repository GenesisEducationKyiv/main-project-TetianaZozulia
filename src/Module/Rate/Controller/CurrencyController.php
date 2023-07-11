<?php

declare(strict_types=1);

namespace App\Module\Rate\Controller;

use App\Controller\BaseController;
use App\Module\Rate\Map\CurrencyResource;
use App\Module\Rate\Map\Rate;
use App\Module\Rate\Service\BusinessCase\GetRateBusinessCase;
use App\Module\Rate\Service\BusinessCase\UpdateRateBusinessCase;
use App\Serializer\JsonSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CurrencyController extends BaseController
{
    public function __construct(
        private GetRateBusinessCase $getRateBusinessCase,
        private UpdateRateBusinessCase $updateRateBusinessCase,
        protected SerializerInterface $serializer,
        protected JsonSerializer $jsonSerializer,
        private Rate $rateMapper,
        private CurrencyResource $currencyResourceMapper,
    ) {
        parent::__construct($jsonSerializer, $jsonSerializer);
    }

    #[Route('/api/rate', name: 'rate', methods: 'GET')]
    public function rate(Request $request): JsonResponse
    {
        $resource = $this->parseQuery($request, $this->currencyResourceMapper);
        $rate = $this->getRateBusinessCase->execute($resource);
        return new JsonResponse([
            'status' => 'succeed',
            'data' => $this->rateMapper->toArray($rate)
        ]);
    }

    #[Route('/api/rate/update', name: 'rate_update', methods: 'PATCH')]
    public function rateUpdate(Request $request): JsonResponse
    {
        $resource = $this->parseQuery($request, $this->currencyResourceMapper);
        $this->updateRateBusinessCase->execute($resource);

        return new JsonResponse();
    }
}
