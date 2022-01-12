<?php

declare(strict_types=1);

namespace JCIT\secrets\tests;

use JCIT\secrets\SecretOccurrence;
use PHPUnit\Framework\TestCase;

/**
 * @covers \JCIT\secrets\SecretOccurrence
 */
class SecretOccurrenceTest extends TestCase
{
    public function test(): void
    {
        $file = 'testFile';
        $line = 1;
        $default = 'default';

        $secretOccurrence = new SecretOccurrence(
            $file,
            $line,
            $default
        );

        self::assertEquals($file, $secretOccurrence->getFile());
        self::assertEquals($line, $secretOccurrence->getLine());
        self::assertEquals($default, $secretOccurrence->getDefault());
    }
}
