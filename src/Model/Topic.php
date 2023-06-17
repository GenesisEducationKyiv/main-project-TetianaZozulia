<?php declare(strict_types=1);

namespace App\Model;

use JetBrains\PhpStorm\Pure;

class Topic
{
    const AVAILABLE_TOPICS = [
        'currency' => 'currency_subscribers.json',
    ];

    public function __construct(private string $name)
    {
        if (!array_key_exists($name, self::AVAILABLE_TOPICS)) {
            throw new \InvalidArgumentException('Undefined topic');
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    #[Pure] public function getFileName(): string
    {
        return self::AVAILABLE_TOPICS[$this->getName()];
    }

}
