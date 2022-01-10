<?php
declare(strict_types=1);

namespace JCIT\secrets\storages;

use JCIT\secrets\interfaces\StorageInterface;

class Filesystem implements StorageInterface
{
    public function __construct(
        private string $basePath
    ) {
    }

    private function filePath(string $secret): string
    {
        return rtrim($this->basePath, '/') . '/' . ltrim($secret, '/');
    }

    public function get(string $secret): string|int|null
    {
        $file = $this->filePath($secret);
        if (!is_file($file)) {
            return null;
        }
        return file_get_contents($file);
    }

    public function prepare(string $secret, array $occurrences): void
    {
        $file = $this->filePath($secret);
        if (!is_file($file)) {
            touch($file);
        }
    }
}
