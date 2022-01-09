<?php
declare(strict_types=1);

namespace JCIT\secrets\exceptions;

use Exception;

class SecretsException extends Exception
{
    public static function notFound($secret): self
    {
        return new self('Secret could not be found: ' . $secret);
    }
}
