<?php

namespace OZiTAG\Tager\Backend\Export\Utils;

use App\Enums\FileScenario;
use Illuminate\Support\Facades\App;
use Ozerich\FileStorage\Storage;

class CsvProcessor
{
    public static function saveToFile(array $rows)
    {
        $filename = uniqid() . '.csv';
        $filePath = storage_path($filename);

        $f = fopen($filePath, 'w+');
        foreach ($rows as $row) {
            fputcsv($f, $row);
        }
        fclose($f);

        /** @var Storage $fileStorage */
        $fileStorage = App::make(Storage::class);

        $file = $fileStorage->createFromLocalFile($filePath, config('tager-export.fileScenario'));

        @unlink($filePath);

        return $file;
    }
}
