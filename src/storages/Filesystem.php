<?php
declare(strict_types=1);

namespace JCIT\secrets\storages;

use JCIT\secrets\interfaces\StorageInterface;

class Filesystem implements StorageInterface
{
    private array $_cache = [];

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
        if (array_key_exists($secret, $this->_cache)) {
            return $this->_cache[$secret];
        }

        $file = $this->filePath($secret);
        if (!is_file($file)) {
            $result = null;
        } else {
            $result = file_get_contents($file);
        }

        $this->_cache[$secret] = $result;
        return $this->_cache[$secret];
    }

    public function prepare(string $secret, array $occurrences): void
    {
        $file = $this->filePath($secret);
        if (!is_file($file)) {
            if (!file_exists(dirname($file))) {
                mkdir(dirname($file), 0777, true);
            }

            touch($file);
        }
    }
}
