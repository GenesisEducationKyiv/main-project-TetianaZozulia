<?php declare(strict_types=1);

namespace App\Service\Storage;

class FileService implements StorageServiceInterface
{
    private string $ratePath;

    public function __construct(private string $path)
    {
        $this->ratePath = $_SERVER['DOCUMENT_ROOT'] . $path;
    }

    public function read(string $name): string
    {
        return file_get_contents($this->ratePath . $name, true);
    }

    public function write(string $name, string $data): void
    {
        file_put_contents($this->ratePath . $name, $data);
    }

    public function copy(string $oldName, string $newName): bool
    {
        return copy($this->ratePath . $oldName, $this->ratePath . $newName);
    }

    public function delete(string $name): bool
    {
        if ($this->isFileExist($name)) {
            return unlink($this->ratePath . $name);
        }
        return true;
    }

    public function isFileExist(string $name): bool
    {
        return file_exists($this->ratePath . $name);
    }
}
