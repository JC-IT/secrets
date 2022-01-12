<?php

declare(strict_types=1);

namespace JCIT\secrets\interfaces;

use JCIT\secrets\exceptions\SecretsException;

interface SecretsInterface
{
    /**
     * Get a secret, returns default on empty
     */
    public function get(string $secret, string|int|bool|null $default = null): string|int|bool|null;

    /**
     * Get a secret, throw SecretsException::notFound($secret) on not found
     * @throws SecretsException
     */
    public function getAndThrowOnNull(string $secret): string|int|bool;
}
