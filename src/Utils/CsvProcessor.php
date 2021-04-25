<?php

namespace OZiTAG\Tager\Backend\Export\Utils;

use Illuminate\Support\Facades\App;
use Ozerich\FileStorage\Storage;
use OZiTAG\Tager\Backend\Export\TagerExport;
use App\Enums\FileScenario;

class CsvProcessor
{
    public static function saveToFile(array $rows, string $filename, string $delimeter)
    {
        $tmpFilename = uniqid() . '.csv';
        $filePath = storage_path($tmpFilename);

        $f = fopen($filePath, 'w+');
        fputs($f, $bom = chr(0xEF) . chr(0xBB) . chr(0xBF));
        foreach ($rows as $row) {
            fputcsv($f, $row, $delimeter);
        }
        fclose($f);

        /** @var Storage $fileStorage */
        $fileStorage = App::make(Storage::class);

        $file = $fileStorage->createFromLocalFile($filePath, TagerExport::getFileScenario(), $filename);

        @unlink($filePath);

        return $file;
    }
}
