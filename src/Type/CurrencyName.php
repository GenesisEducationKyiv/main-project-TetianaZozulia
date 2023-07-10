<?php

declare(strict_types=1);

namespace App\Type;

use http\Exception\InvalidArgumentException;

class CurrencyName
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(private string $str)
    {
        $strLen = mb_strlen($str);
        if ($strLen < 1) {
            throw new InvalidArgumentException('String length Exception: ' . $str . '(Length: ' . $strLen . ')');
        }
        if ($strLen > 7) {
            throw new InvalidArgumentException('String length Exception: ' . $str . '(Length: ' . $strLen . ')');
        }
    }

    public function toString(): string
    {
        return $this->str;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
