<?php

declare(strict_types=1);

namespace JCIT\secrets\tests\storages;

use JCIT\secrets\interfaces\StorageInterface;
use JCIT\secrets\storages\Chained;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \JCIT\secrets\storages\Chained
 */
class ChainedTest extends TestCase
{
    private MockObject $storage1;
    private MockObject $storage2;
    
    public function dataProviderGet(): array
    {
        return [
            'First return' => [
                'testValue1',
                'testValue2',
                'testValue1',
            ],
            'Second return' => [
                null,
                'testValue2',
                'testValue2',
            ],
            'None return' => [
                null,
                null,
                null,
            ]
        ];
    }

    private function getStorage(): Chained
    {
        $this->storage1 = $this->getMockBuilder(StorageInterface::class)->getMock();
        $this->storage2 = $this->getMockBuilder(StorageInterface::class)->getMock();

        return new Chained($this->storage1, $this->storage2);
    }

    public function testCache(): void
    {
        $secret = 'testSecret';
        $value = 'testValue';
        $storage = $this->getStorage();
        $this->storage1->expects(self::once())
            ->method('get')
            ->with($secret)
            ->willReturnOnConsecutiveCalls($value, null);

        $storage->get($secret);
        $result = $storage->get($secret);

        self::assertEquals($value, $result);
    }

    /**
     * @dataProvider dataProviderGet
     */
    public function testGet(string|int|bool|null $value1, string|int|bool|null  $value2, string|int|bool|null  $expectedValue): void
    {
        $secret = 'testSecret';
        $storage = $this->getStorage();
        $this->storage1->expects(self::once())
            ->method('get')
            ->with($secret)
            ->willReturn($value1);

        $this->storage2->expects(is_null($value1) ? self::once() : self::never())
            ->method('get')
            ->with($secret)
            ->willReturn($value2);

        $result = $storage->get($secret);

        self::assertEquals($expectedValue, $result);
    }
}
