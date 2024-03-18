<?php

namespace App\Tests\Trait;

trait FileSystemAssertions
{
    public static function assertFsFileExists(string $location): void
    {
        self::assertTrue(self::$fileSystem->storage->fileExists($location), "Failed asserting that file at $location exists");
    }

    public static function assertFsFileDoesNotExists(string $location): void
    {
        self::assertFalse(self::$fileSystem->storage->fileExists($location), "Failed asserting that file at $location does not exists");
    }
}
