<?php

declare(strict_types=1);

namespace App\Module\Rate\Repository;

use App\Module\Rate\Model\Rate as RateModel;
use App\Module\Rate\Map\Rate as RateMapper;
use App\Module\Rate\Model\ResourceModel\CurrencyResourceInterface;
use App\Serializer\JsonSerializer;
use App\Storage\FileStorage\FileStorageInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class RateRepository implements RateRepositoryInterface
{
    public function __construct(
        private RateMapper $rateMapper,
        private FileStorageInterface $service,
        private JsonSerializer $serializer,
        private string $fileName,
        private CurrencyResourceInterface $defaultCurrency
    ) {
    }

    public function read(?CurrencyResourceInterface $currencyResource = null): RateModel
    {
        $currencyResource = $currencyResource ?? $this->defaultCurrency;
        $fileName = sprintf(
            $this->fileName,
            strtolower($currencyResource->getFrom()->toString()),
            strtolower($currencyResource->getTo()->toString()),
        );
        if (!$this->service->isFileExist($fileName)) {
            throw new FileNotFoundException(sprintf('File %s not found', $fileName));
        }
        $rate = $this->service->read($fileName);
        return $this->rateMapper->fromArray(
            $this->serializer->deserialize($rate)
        );
    }

    public function write(RateModel $rate): void
    {
        $rateArray = $this->rateMapper->toArray($rate);
        $this->service->write(
            sprintf(
                $this->fileName,
                strtolower($rate->getFromCurrencyName()->toString()),
                strtolower($rate->getToCurrencyName()->toString()),
            ),
            $this->serializer->serialize($rateArray)
        );
    }
}
