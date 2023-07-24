<?php

declare(strict_types=1);

namespace App\Model;

class Topic
{
    public const AVAILABLE_TOPICS = [
        'currency' => '/currency_subscribers.json',
    ];

    public function __construct(
        private string $name
    ) {
        if (!array_key_exists($name, self::AVAILABLE_TOPICS)) {
            throw new \InvalidArgumentException('Undefined topic');
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFileName(): string
    {
        return self::AVAILABLE_TOPICS[$this->getName()];
    }

    public function getFilePath(): string
    {
        return '/subscribers/' . self::AVAILABLE_TOPICS[$this->getName()];
    }
}
