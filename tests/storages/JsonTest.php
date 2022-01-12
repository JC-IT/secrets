<?php

declare(strict_types=1);

namespace JCIT\secrets\tests\storages;

use bovigo\vfs\vfsDirectory;
use bovigo\vfs\vfsFile;
use bovigo\vfs\vfsStream;
use JCIT\secrets\storages\Json;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;

/**
 * @covers \JCIT\secrets\storages\Json
 */
class JsonTest extends TestCase
{
    private string $file = 'secrets.json';
    private vfsDirectory $filesystem;

    public function dataProviderGet(): array
    {
        return [
            'Existing value' => [
                [
                    'testSecret' => 'testValue',
                ],
                'testSecret',
                'testValue'
            ],
            'Not existing value' => [
                [
                    'testSecret2' => 'testValue',
                ],
                'testSecret',
                null
            ]
        ];
    }

    public function dataProviderPrepare(): array
    {
        return [
            'New secret' => [
                [],
                'testSecret',
                ['testSecret' => ''],
            ],
            'Add secret' => [
                ['testSecret2' => 'testValue2'],
                'testSecret',
                ['testSecret' => '', 'testSecret2' => 'testValue2'],
            ],
            'Duplicate secret' => [
                ['testSecret' => 'testValue'],
                'testSecret',
                ['testSecret' => 'testValue'],
            ],
        ];
    }

    private function getStorage(): Json
    {
        return new Json(vfsStream::url($this->file));
    }

    public function setUp(): void
    {
        $this->filesystem = vfsStream::setup('');

        parent::setUp();
    }

    /**
     * @dataProvider dataProviderGet
     * @param array<string, string|int|bool|null> $fileContent
     */
    public function testGet(array $fileContent, string $secret, string|int|bool|null $expectedValue): void
    {
        /** @var string $jsonEncodedFileContent */
        $jsonEncodedFileContent = json_encode($fileContent);
        vfsStream::newFile($this->file)->at($this->filesystem)->setContent($jsonEncodedFileContent);

        self::assertEquals($expectedValue, $this->getStorage()->get($secret));
    }

    public function testGetNoFile(): void
    {
        self::assertFalse($this->filesystem->hasChild($this->file));

        self::assertNull($this->getStorage()->get('testSecret'));
    }

    /**
     * @dataProvider dataProviderPrepare
     * @param array<string, string|int|bool|null> $fileContentBefore
     * @param array<string, string|int|bool|null> $fileContentAfter
     */
    public function testPrepare(array $fileContentBefore, string $secret, array $fileContentAfter): void
    {
        /** @var string $jsonEncodedFileContentBefore */
        $jsonEncodedFileContentBefore = json_encode($fileContentBefore);
        vfsStream::newFile($this->file)->at($this->filesystem)->setContent($jsonEncodedFileContentBefore);

        $this->getStorage()->prepare($secret, []);

        /** @var string $jsonEncodedFileContentAfter */
        $jsonEncodedFileContentAfter = file_get_contents(vfsStream::url($this->file));
        self::assertEquals($fileContentAfter, json_decode($jsonEncodedFileContentAfter, true));
    }
}
