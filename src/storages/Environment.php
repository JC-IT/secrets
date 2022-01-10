<?php
declare(strict_types=1);

namespace JCIT\secrets\storages;

use JCIT\secrets\interfaces\StorageInterface;

class Environment implements StorageInterface
{
    public function get(string $secret): string|int|null
    {
        $secrets = getenv();
        return $secrets[$secret] ?? null;
    }

    public function prepare(string $secret, array $occurrences): void
    {
    }
}
