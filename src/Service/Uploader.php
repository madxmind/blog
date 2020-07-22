<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class Uploader implements UploaderInterface
{
    private $uploadsAbsoluteDir;
    private $uploadsRelativeDir;

    public function __construct(string $uploadsAbsoluteDir, string $uploadsRelativeDir)
    {
        $this->uploadsAbsoluteDir = $uploadsAbsoluteDir;
        $this->uploadsRelativeDir = $uploadsRelativeDir;
    }

    /**
     * @param  UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        $filename = Uuid::v4() . '.' . $file->getClientOriginalExtension();
        $file->move($this->uploadsAbsoluteDir, $filename);
        return $this->uploadsRelativeDir . '/' . $filename;
    }
}
