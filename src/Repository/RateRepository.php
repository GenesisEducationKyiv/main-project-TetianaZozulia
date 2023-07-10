<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Rate as RateModel;
use App\Map\Rate as RateMapper;
use App\Model\ResourceModel\CurrencyResource;
use App\Model\ResourceModel\CurrencyResourceInterface;
use App\Serializer\JsonSerializer;
use App\Service\Storage\StorageServiceInterface;
use App\Type\CurrencyName;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class RateRepository implements RateRepositoryInterface
{
    private CurrencyResourceInterface $defaultCurrency;

    public function __construct(
        private RateMapper $rateMapper,
        private StorageServiceInterface $service,
        private JsonSerializer $serializer,
        private string $fileName,
        private string $defaultCurrencyFrom,
        private string $defaultCurrencyTo,
    ) {
        $this->defaultCurrency = new CurrencyResource(
            new CurrencyName($this->defaultCurrencyFrom),
            new CurrencyName($this->defaultCurrencyTo),
        );
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
