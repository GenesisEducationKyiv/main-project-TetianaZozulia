<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Rate as RateModel;
use App\Map\Rate as RateMapper;
use App\Service\Storage\StorageServiceInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

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
        if (!$this->service->isFileExist($this->fileName)) {
            throw new FileNotFoundException(sprintf('File %s not found', $this->fileName));
        }
        $rate = $this->service->read($this->fileName);
        return $this->rateMapper->fromArray(json_decode($rate, true));
    }

    public function write(RateModel $rate): void
    {
        $this->service->write($this->fileName, json_encode($this->rateMapper->toArray($rate)));
    }
}
