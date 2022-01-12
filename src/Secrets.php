<?php

declare(strict_types=1);

namespace JCIT\secrets;

use JCIT\secrets\exceptions\SecretsException;
use JCIT\secrets\interfaces\SecretsInterface;
use JCIT\secrets\interfaces\StorageInterface;

class Secrets implements SecretsInterface
{
    public function __construct(
        private StorageInterface $storage
    ) {
    }

    public function get(string $secret, string|int|bool|null $default = null): string|int|bool|null
    {
        return $this->storage->get($secret) ?? $default;
    }

    public function getAndThrowOnNull(string $secret): string|int|bool
    {
        $result = $this->get($secret);

        if (is_null($result)) {
            throw new SecretsException('Secret could not be found: ' . $secret);
        }

        return $result;
    }
}
