<?php declare(strict_types=1);

namespace App\Type;

use http\Exception\InvalidArgumentException;

class CurrencyName
{
    /**
     * @param string $str
     */
    public function __construct(private string $str)
    {
        $strLen = mb_strlen($str);
        if ($strLen < 1) {
            throw new InvalidArgumentException('String length Exception: ' . $str . '(Length: ' . $strLen . ')');
        }
        if ($strLen > 5) {
            throw new InvalidArgumentException('String length Exception: ' . $str . '(Length: ' . $strLen . ')');
        }
        $this->str = $str;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->str;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
