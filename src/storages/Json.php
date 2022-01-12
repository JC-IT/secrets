<?php
declare(strict_types=1);

namespace JCIT\secrets\storages;

use JCIT\secrets\interfaces\StorageInterface;

class Json implements StorageInterface
{
    private array $_cache;

    public function __construct(
        private string $file
    ) {
    }

    public function get(string $secret): string|int|null
    {
        if (!is_file($this->file)) {
            return null;
        }

        if (!isset($this->_cache)) {
            $this->_cache = json_decode(file_get_contents($this->file), true);
        }

        return $this->_cache[$secret] ?? null;
    }

    public function prepare(string $secret, array $occurrences): void
    {
        $secrets = [];
        if (is_file($this->file)) {
            $secret = json_decode(file_get_contents($this->file));
        }

        $secrets[$secret] = '';

        file_put_contents($this->file, json_encode($secrets));
    }
}
