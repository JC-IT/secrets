<?php

declare(strict_types=1);

namespace JCIT\secrets\tests;

use JCIT\secrets\exceptions\SecretsException;
use JCIT\secrets\interfaces\StorageInterface;
use JCIT\secrets\Secrets;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \JCIT\secrets\Secrets
 */
class SecretsTest extends TestCase
{
    private MockObject $storage;

    public function dataProviderGet(): array
    {
        return [
            'Value' => [
                'testValue',
                false,
            ],
            'Null value' => [
                null,
                true,
            ],
        ];
    }

    public function dataProviderGetAndThrow(): array
    {
        return [
            'Value' => [
                'testValue',
                false,
            ],
            'Null value' => [
                null,
                true,
            ],
        ];
    }

    private function getSecrets(): Secrets
    {
        $this->storage = $this->getMockBuilder(StorageInterface::class)->getMock();
        return new Secrets($this->storage);
    }

    /**
     * @dataProvider dataProviderGet
     */
    public function testGet(string|int|bool|null $value, bool $willReturnDefault): void
    {
        $secrets = $this->getSecrets();
        $secret = 'testSecret';
        $default = 'defaultValue';

        $this->storage->expects(self::once())
            ->method('get')
            ->with($secret)
            ->willReturn($value);

        self::assertEquals(!$willReturnDefault ? $value : $default, $secrets->get($secret, $default));
    }

    /**
     * @dataProvider dataProviderGetAndThrow
     */
    public function testGetAndThrow(string|int|bool|null $value, bool $willThrow): void
    {
        $secrets = $this->getSecrets();
        $secret = 'testSecret';

        $this->storage->expects(self::once())
            ->method('get')
            ->with($secret)
            ->willReturn($value);

        if ($willThrow) {
            self::expectException(SecretsException::class);
        }

        $result = $secrets->getAndThrowOnNull($secret);

        if (!$willThrow) {
            self::assertEquals($value, $result);
        }
    }
}
