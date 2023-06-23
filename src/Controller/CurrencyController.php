<?php declare(strict_types=1);

namespace App\Controller;

use App\Map\Rate;
use App\Service\BusinessCase\GetRateBusinessCase;
use App\Service\BusinessCase\UpdateRateBusinessCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CurrencyController extends AbstractController
{
    public function __construct(
        private GetRateBusinessCase $getRateBusinessCase,
        private UpdateRateBusinessCase $updateRateBusinessCase,
        private Rate $rateMapper,
    ) {
    }

    #[Route('/api/rate', name: 'rate', methods: 'GET')]
    public function rate(): JsonResponse
    {
        $rate = $this->getRateBusinessCase->execute();
        $data = $this->rateMapper->toArray($rate);
        return new JsonResponse([
            'status' => 'succeed',
            'error' => [],
            'data' => $data
        ]);
    }

    #[Route('/api/rate/update', name: 'rate_update', methods: 'PATCH')]
    public function rateUpdate(): JsonResponse
    {
        $error = [];
        try {
            $this->updateRateBusinessCase->execute();
        } catch (\HttpException $exception) {
            $error[] = $exception->getMessage();
        }

        return new JsonResponse([
            'status' => count($error) > 0 ? 'failed' : 'succeed',
            'error' => $error,
        ]);
    }
}
