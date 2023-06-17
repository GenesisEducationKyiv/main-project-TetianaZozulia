<?php declare(strict_types=1);

namespace App\Controller;

use App\Map\Rate;
use App\Repository\RateRepository;
use App\Service\CurrencyApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CurrencyController extends AbstractController
{
    public function __construct(
        private RateRepository $rateRepository,
        private Rate $rateMapper,
        private CurrencyApiClient $apiClient
    )
    {
    }

    #[Route('/rate', name: 'rate', methods: 'GET')]
    public function rate(): JsonResponse
    {
        $error = [];
        try {
            $data = $this->rateRepository->read();
            $data = $this->rateMapper->toArray($data);
        } catch (\Throwable $exception) {
            $rate = $this->apiClient->getRate();
            $this->rateRepository->write($rate);
            $data = $this->rateMapper->toArray($rate);
        }
        return new JsonResponse([
            'status' => count($error) > 0 ? 'failed' : 'succeed',
            'error' => $error,
            'data' => $data
        ]);
    }

    #[Route('/rate/update', name: 'rate_update', methods: 'PATCH')]
    public function rateUpdate(): JsonResponse
    {
        $error = [];
        try {
            $rate = $this->apiClient->getRate();
            $this->rateRepository->write($rate);
        } catch (\HttpException $exception) {
            $error[] = $exception->getMessage();
        }

        return new JsonResponse([
            'status' => count($error) > 0 ? 'failed' : 'succeed',
            'error' => $error,
        ]);
    }

}
