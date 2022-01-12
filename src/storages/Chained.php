<?php
declare(strict_types=1);

namespace JCIT\secrets\storages;

use JCIT\secrets\exceptions\StorageException;
use JCIT\secrets\interfaces\StorageInterface;

class Chained implements StorageInterface
{
    private $_cache = [];

    public function __construct(
        private array $storages,
        private bool $returnFirstFound = true,
    ) {
        foreach ($this->storages as $storage) {
            if (!$storage instanceof StorageInterface) {
                throw new StorageException('Storage must be instance of ' . StorageInterface::class);
            }
        }
    }

    public function get(string $secret): string|int|null
    {
        if (array_key_exists($secret, $this->_cache)) {
            return $this->_cache[$secret];
        }

        foreach ($this->storages as $storage) {
            $result = $storage->get($secret);

            if (!is_null($result) && $this->returnFirstFound) {
                $this->_cache[$secret] = $result;
                return $result;
            }
        }

        $this->_cache[$secret] = $result;
        return $result;
    }

    public function prepare(string $secret, array $occurrences): void
    {
        throw new StorageException('Please implement your own prepare.');
    }
}
