<?php

namespace OZiTAG\Tager\Backend\Export\Structures;

use Ozerich\FileStorage\Models\File;

class ExportResult
{
    protected File $file;

    protected string $fileName;

    protected ?string $message;

    public function __construct(File $file, string $fileName, ?string $message = null)
    {
        $this->file = $file;

        $this->fileName = $fileName;

        $this->message = $message;
    }

    public function getFileId(): ?int
    {
        return $this->file ? $this->file->id : null;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
