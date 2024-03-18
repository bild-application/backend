<?php

namespace App\Facade;

use League\Flysystem\FilesystemOperator;

final class FileSystemFacade
{
    protected static FilesystemOperator $storageSingleton;

    public function __construct(
        FilesystemOperator $defaultStorage
    ) {
        if (!isset(self::$storageSingleton)) {
            self::$storageSingleton = $defaultStorage;
        }
    }

    public function getStorage(): FilesystemOperator
    {
        return self::$storageSingleton;
    }
}
