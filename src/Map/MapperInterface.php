<?php

declare(strict_types=1);

namespace App\Map;

interface MapperInterface
{
    public function toArray($object): array;

    public function fromArray(array $ar);
}
