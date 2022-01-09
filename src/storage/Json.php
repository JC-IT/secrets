<?php
declare(strict_types=1);

namespace JCIT\secrets\storage;

use JCIT\secrets\interfaces\StorageInterface;

class Json implements StorageInterface
{
    public function __construct(
        private string $file
    ) {
    }

    public function get(string $secret): string|int|null
    {
        if (!is_file($this->file)) {
            return null;
        }

        $secrets = json_decode(file_get_contents($this->file));
        return $secrets[$secret] ?? null;
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
