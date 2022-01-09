<?php
declare(strict_types=1);

namespace JCIT\secrets;

use common\exceptions\SecretsException;
use JCIT\secrets\interfaces\SecretsInterface;
use JCIT\secrets\interfaces\StorageInterface;

class Secrets implements SecretsInterface
{
    public function __construct(
        private StorageInterface $storage
    ) {
    }

    public function get(string $secret, string|int|null $default = null): string|int|null
    {
        return $this->storage->get($secret) ?? $default;
    }

    public function getAndThrowOnEmpty(string $secret): string|int
    {
        $result = $this->get($secret);

        if (is_null($result)) {
            throw SecretsException::notFound($secret);
        }

        return $result;
    }
}
