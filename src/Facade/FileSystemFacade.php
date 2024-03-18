<?php

namespace App\Facade;

use League\Flysystem\FilesystemOperator;

readonly final class FileSystemFacade
{
    public FilesystemOperator $storage;

    public function __construct(
        FilesystemOperator $defaultStorage
    ) {
        $this->storage = $defaultStorage;
    }
}
