<?php declare(strict_types=1);

namespace App\Controller;

use App\Map\Rate;
use App\Repository\RateRepository;
use App\Service\CurrencyApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CurrencyController extends AbstractController
{
    public function __construct(
        private RateRepository $rateRepository,
        private Rate $rateMapper,
        private CurrencyApiClient $apiClient
    ) {
    }

    #[Route('/rate', name: 'rate', methods: 'GET')]
    public function rate(): JsonResponse
    {
        try {
            $rate = $this->rateRepository->read();
        } catch (FileNotFoundException $exception) {
            $rate = $this->apiClient->getRate();
            $this->rateRepository->write($rate);
        }
        return new JsonResponse([
            'status' => 'succeed',
            'data' => $this->rateMapper->toArray($rate)
        ]);
    }

    #[Route('/rate/update', name: 'rate_update', methods: 'PATCH')]
    public function rateUpdate(): JsonResponse
    {
        try {
            $rate = $this->apiClient->getRate();
            $this->rateRepository->write($rate);
        } catch (\HttpException $exception) {
            $error = $exception->getMessage();
        }

        return new JsonResponse([
            'status' => isset($error) ? 'failed' : 'succeed',
            'error' => isset($error) ? [$error] : [],
        ]);
    }
}
