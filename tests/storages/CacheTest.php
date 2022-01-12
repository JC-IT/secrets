<?php

declare(strict_types=1);

namespace JCIT\secrets\tests\storages;

use JCIT\secrets\storages\Cache;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \JCIT\secrets\storages\Cache
 */
class CacheTest extends TestCase
{
    public function dataProviderGet(): array
    {
        return [
            'Empty' => [
                [],
                'testSecret',
                null,
            ],
            'Existing' => [
                ['testSecret' => 'testValue'],
                'testSecret',
                'testValue',
            ]
        ];
    }

    /**
     * @dataProvider dataProviderGet
     * @param array<string, string|int|bool|null> $cache
     */
    public function testGet(array $cache, string $secret, string|int|bool|null $expectedValue): void
    {
        $storage = new Cache($cache);

        self::assertEquals($expectedValue, $storage->get($secret));
    }
}
