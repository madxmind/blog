<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploaderInterface
{
    /**
     * @param  UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file): string;
}
