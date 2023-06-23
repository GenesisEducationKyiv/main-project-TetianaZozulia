<?php declare(strict_types=1);

namespace App\Service;

class FileService implements StorageServiceInterface
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

    public function copy(string $oldName, string $newName): bool
    {
        return copy($this->path . $oldName, $this->path . $newName);
    }

    public function delete(string $name): bool
    {
        return unlink($this->path . $name);
    }

    public function isFileExist(string $name): bool
    {
        return file_exists($this->path . $name);
    }
}
