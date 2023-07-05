<?php

declare(strict_types=1);

namespace App\Controller;

use App\Map\CurrencyResource;
use App\Map\Rate;
use App\Service\BusinessCase\GetRateBusinessCase;
use App\Service\BusinessCase\UpdateRateBusinessCase;
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
        private Rate $rateMapper,
        private CurrencyResource $currencyResourceMapper,
    ) {
        parent::__construct($serializer);
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
