<?php

namespace App\Facade;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

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

    /**
     * @param UploadedFile $file
     *
     * @throws \League\Flysystem\FilesystemException
     * @return string Stored file path
     */
    public function store(UploadedFile $file, ?string $folder = ""): string
    {
        $filename = Uuid::v4() . '.' . $file->guessExtension();

        $filepath = rtrim($folder, '/') . '/' . $filename;

        $this->getStorage()->write($filepath, $file->getContent());

        return $filepath;
    }
}
