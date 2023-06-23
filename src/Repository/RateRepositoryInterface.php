<?php declare(strict_types=1);

namespace App\Repository;

use App\Model\Rate as RateModel;

interface RateRepositoryInterface
{
    public function read(): RateModel;

    public function write(RateModel $rate): void;
}
