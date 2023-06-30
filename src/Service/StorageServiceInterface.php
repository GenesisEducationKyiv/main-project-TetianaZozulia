<?php declare(strict_types=1);

namespace App\Service;

interface StorageServiceInterface
{
    public function read(string $name): string;

    public function write(string $name, string $data): void;

    public function copy(string $oldName, string $newName): bool;

    public function delete(string $name): bool;

    public function isFileExist(string $name): bool;
}
