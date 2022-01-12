<?php

declare(strict_types=1);

namespace JCIT\secrets;

class SecretOccurrence
{
    public function __construct(
        private string $file,
        private int $line,
        private string|int|null $default = null,
    ) {
    }

    public function getDefault(): string|int|null
    {
        return $this->default;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function getLine(): int
    {
        return $this->line;
    }
}
