<?php

declare(strict_types=1);

namespace App\Model;

use JetBrains\PhpStorm\Pure;

class Topic
{
    public const AVAILABLE_TOPICS = [
        'currency' => '/currency_subscribers.json',
    ];

    private const PROCESSING_TOPICS = [
        'currency-processing' => '/processing/currency_subscribers.json',
    ];

    public function __construct(
        private string $name
    ) {
        if (
            !array_key_exists($name, self::AVAILABLE_TOPICS)
            && !array_key_exists($name, self::PROCESSING_TOPICS)
        ) {
            throw new \InvalidArgumentException('Undefined topic');
        }
    }

    public function createForProcessing(): Topic
    {
        return new self($this->name . '-processing');
    }

    public function getName(): string
    {
        return $this->name;
    }

    #[Pure] public function getFileName(): string
    {
        return self::AVAILABLE_TOPICS[$this->getName()] ?? self::PROCESSING_TOPICS[$this->getName()];
    }
}
