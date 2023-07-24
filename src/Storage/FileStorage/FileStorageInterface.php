<?php

declare(strict_types=1);

namespace App\Storage\FileStorage;

interface FileStorageInterface
{
    public function read(string $name): string;

    public function write(string $name, string $data): void;

    public function delete(string $name): bool;

    public function isFileExist(string $name): bool;
}
