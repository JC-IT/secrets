<?php

declare(strict_types=1);

namespace JCIT\secrets\storages;

use JCIT\secrets\interfaces\StorageInterface;

class Cache implements StorageInterface
{
    /**
     * @param array<string, string|int|bool|null> $cache
     */
    public function __construct(
        private array $cache,
    ) {
    }

    public function get(string $secret): string|int|bool|null
    {
        return $this->cache[$secret] ?? null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function prepare(string $secret, array $occurrences): void
    {
        // Must not do anything
    }
}
