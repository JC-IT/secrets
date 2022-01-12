<?php

declare(strict_types=1);

namespace JCIT\secrets\storages;

use JCIT\secrets\exceptions\StorageException;
use JCIT\secrets\interfaces\StorageInterface;

class Json implements StorageInterface
{
    /**
     * @var array<string, string|int|bool|null>
     */
    private array $_cache;

    public function __construct(
        private string $file
    ) {
    }

    public function get(string $secret): string|int|bool|null
    {
        if (!is_file($this->file)) {
            return null;
        }

        if (!isset($this->_cache)) {
            $fileContents = file_get_contents($this->file);

            // @codeCoverageIgnoreStart
            if ($fileContents === false) {
                throw new StorageException('Failed reading ' . $this->file);
            }
            // @codeCoverageIgnoreEnd

            /** @var array<string, string|int|bool|null> $secrets */
            $secrets = json_decode($fileContents, true);

            $this->_cache = $secrets;
        }

        return $this->_cache[$secret] ?? null;
    }

    public function prepare(string $secret, array $occurrences): void
    {
        $secrets = [];
        if (is_file($this->file)) {
            $fileContents = file_get_contents($this->file);

            // @codeCoverageIgnoreStart
            if ($fileContents === false) {
                throw new StorageException('Failed reading ' . $this->file);
            }
            // @codeCoverageIgnoreEnd

            /** @var array<string, string|int|bool|null> $secrets */
            $secrets = json_decode($fileContents, true);
        }

        $secrets[$secret] = $secrets[$secret] ?? '';

        file_put_contents($this->file, json_encode($secrets));
    }
}
