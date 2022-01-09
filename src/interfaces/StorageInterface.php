<?php
declare(strict_types=1);

namespace JCIT\secrets\interfaces;

interface StorageInterface
{
    /**
     * Fetch the secret, return null if none found
     */
    public function get(string $secret): string|int|null;

    /**
     * prepare the secret, this will be called when the extractor is used
     */
    public function prepare(string $secret, array $occurrences): void;
}
