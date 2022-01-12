<?php

declare(strict_types=1);

namespace JCIT\secrets\tests\storages;

use bovigo\vfs\vfsDirectory;
use bovigo\vfs\vfsStream;
use JCIT\secrets\storages\Filesystem;
use PHPUnit\Framework\TestCase;

/**
 * @covers \JCIT\secrets\storages\Filesystem
 */
class FilesystemTest extends TestCase
{
    private string $directory = 'secrets';
    private vfsDirectory $filesystem;

    public function dataProviderGet(): array
    {
        return [
            'File' => [
                'testSecret',
                'testValue',
                'testSecret',
                'testValue',
            ],
            'No file' => [
                null,
                null,
                'testSecret',
                null,
            ]
        ];
    }

    private function getStorage(): Filesystem
    {
        return new Filesystem(vfsStream::url($this->directory));
    }

    public function setUp(): void
    {
        $this->filesystem = vfsStream::setup($this->directory);

        parent::setUp();
    }

    /**
     * @dataProvider dataProviderGet
     */
    public function testGet(string|null $file, string|null $fileContent, string $secret, string|int|bool|null $expectedValue): void
    {
        if (!is_null($file) && !is_null($fileContent)) {
            vfsStream::newFile($file)->at($this->filesystem)->setContent($fileContent);
        }
        
        $result = $this->getStorage()->get($secret);
        self::assertEquals($expectedValue, $result);
    }

    public function testGetCache(): void
    {
        $secret = 'testSecret';
        $value = 'testValue';
        $vfsFile = vfsStream::newFile($secret)->at($this->filesystem)->setContent($value);

        $storage = $this->getStorage();

        $storage->get($secret);
        $vfsFile->setContent('');
        $result2 = $storage->get($secret);

        self::assertEquals($value, $result2);
    }

    public function testPrepare(): void
    {
        $testFolder = 'testFolder';
        $testFile = 'testFile';
        $secret = "{$testFolder}/{$testFile}";

        self::assertFalse($this->filesystem->hasChild($secret));

        $this->getStorage()->prepare($secret, []);

        self::assertTrue($this->filesystem->hasChild($secret));
    }
}
