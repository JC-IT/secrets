<?php
declare(strict_types=1);

namespace JCIT\secrets\storages;

use JCIT\secrets\interfaces\StorageInterface;

class Environment implements StorageInterface
{
    private array $_cache;

    public function get(string $secret): string|int|null
    {
        if (!isset($this->_cache)) {
            $this->_cache = getenv();
        }

        return $this->_cache[$secret] ?? null;
    }

    public function prepare(string $secret, array $occurrences): void
    {
    }
}
