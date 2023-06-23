<?php declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\Serializer\SerializerInterface;

class JsonSerializer implements SerializerInterface
{
    public function serialize(mixed $data, string $format = 'json', array $context = []): string
    {
        return json_encode($data);
    }

    public function deserialize(mixed $data, string $type = 'array', string $format = 'json', array $context = []): mixed
    {
        return json_decode($data, true);
    }
}
