<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Rate as RateModel;
use App\Map\Rate as RateMapper;
use App\Service\Storage\StorageServiceInterface;

class RateRepository implements RateRepositoryInterface
{
    public function __construct(
        private RateMapper $rateMapper,
        private StorageServiceInterface $service,
        private string $fileName
    ) {
    }

    public function read(): RateModel
    {
        $json =  $this->service->read($this->fileName);
        return $this->rateMapper->fromArray(json_decode($json, true));
    }

    public function write(RateModel $rate): void
    {
        $this->service->write($this->fileName, json_encode($this->rateMapper->toArray($rate)));
    }
}
