<?php

namespace OZiTAG\Tager\Backend\Export\Utils;

use App\Enums\FileScenario;
use Illuminate\Support\Facades\App;
use Ozerich\FileStorage\Storage;

class CsvProcessor
{
    public static function saveToFile(array $rows, string $filename)
    {
        $tmpFilename = uniqid() . '.csv';
        $filePath = storage_path($tmpFilename);

        $f = fopen($filePath, 'w+');
        fputs($f, chr(0xEF) . chr(0xBB) . chr(0xBF));

        foreach ($rows as $row) {
            fputcsv($f, $row, ';');
        }
        fclose($f);

        /** @var Storage $fileStorage */
        $fileStorage = App::make(Storage::class);

        $file = $fileStorage->createFromLocalFile($filePath, config('tager-export.fileScenario'), $filename);

        @unlink($filePath);

        return $file;
    }
}
