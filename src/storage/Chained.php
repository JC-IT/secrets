<?php
declare(strict_types=1);

namespace JCIT\secrets\storage;

use JCIT\secrets\exceptions\StorageException;
use JCIT\secrets\interfaces\StorageInterface;

class Chained implements StorageInterface
{
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
        foreach ($this->storages as $storage) {
            $result = $storage->get($secret);

            if (!is_null($result) && $this->returnFirstFound) {
                return $result;
            }
        }

        return $result;
    }

    public function prepare(string $secret, array $occurrences): void
    {
        throw new StorageException('Please implement your own prepare.');
    }
}
