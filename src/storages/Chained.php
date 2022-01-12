<?php

declare(strict_types=1);

namespace JCIT\secrets\storages;

use JCIT\secrets\exceptions\StorageException;
use JCIT\secrets\interfaces\StorageInterface;

class Chained implements StorageInterface
{
    /**
     * @var array<string, string|int|bool|null>
     */
    private array $_cache = [];

    /**
     * @var array<StorageInterface>
     */
    private array $storages = [];

    public function __construct(
        StorageInterface ...$storages,
    ) {
        foreach ($storages as $storage) {
            $this->storages[] = $storage;
        }
    }

    public function get(string $secret): string|int|bool|null
    {
        if (array_key_exists($secret, $this->_cache)) {
            return $this->_cache[$secret];
        }

        $result = null;

        foreach ($this->storages as $storage) {
            $result = $storage->get($secret);

            if (!is_null($result)) {
                $this->_cache[$secret] = $result;
                return $result;
            }
        }

        $this->_cache[$secret] = $result;
        return $result;
    }

    /**
     * @codeCoverageIgnore
     */
    public function prepare(string $secret, array $occurrences): void
    {
    }
}
