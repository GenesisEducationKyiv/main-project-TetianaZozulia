<?php

declare(strict_types=1);

namespace App\Storage\FileStorage;

class FileStorage implements FileStorageInterface
{
    public function __construct(private string $path)
    {
    }

    public function read(string $name): string
    {
        return file_get_contents($this->path . $name, true);
    }

    public function write(string $name, string $data): void
    {
        file_put_contents($this->path . $name, $data);
    }

    public function delete(string $name): bool
    {
        if ($this->isFileExist($name)) {
            return unlink($this->path . $name);
        }
        return true;
    }

    public function isFileExist(string $name): bool
    {
        return file_exists($this->path . $name);
    }
}
