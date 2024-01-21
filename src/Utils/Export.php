<?php

namespace OZiTAG\Tager\Backend\Export\Utils;

use Illuminate\Support\Facades\Log;
use OZiTAG\Tager\Backend\Export\Enums\ExportFileFormat;
use OZiTAG\Tager\Backend\Export\Exceptions\ExportNotFoundStrategyException;
use OZiTAG\Tager\Backend\Export\Exceptions\ExportProcessException;
use OZiTAG\Tager\Backend\Export\Exceptions\ExportSaveFileException;
use OZiTAG\Tager\Backend\Export\Structures\ExportResult;
use OZiTAG\Tager\Backend\Export\TagerExport;

class Export
{
    /**
     * @throws ExportProcessException
     * @throws ExportNotFoundStrategyException
     * @throws ExportSaveFileException
     */
    public function run(string $strategyId, string $filename, ExportFileFormat $format, ?array $payload = null, ?array $options = []): ExportResult
    {
        $strategy = TagerExport::getStrategy($strategyId);
        if (!$strategy) {
            throw new ExportNotFoundStrategyException('Strategy "' . $strategyId . '" not found');
        }

        $strategy->setPayload($payload);

        $header = $strategy->getHeader();
        if ($header !== null) {
            $result = [array_values($header)];
        } else {
            $result = [];
        }

        try {
            $strategy->execute();
            $rows = array_merge($result, $strategy->getData());
        } catch (\Exception $exception) {
            throw new ExportProcessException($exception);
        }

        if ($format != ExportFileFormat::Csv) {
            throw new ExportSaveFileException('Only CSV export is supported now');
        }

        try {
            $file = CsvProcessor::saveToFile($rows, $filename, array_key_exists('delimiter', $options) ? $options['delimiter'] : ',');
        } catch (\Exception $exception) {
            throw new ExportSaveFileException('Save file error - ' . $exception->getMessage());
        }

        if (!$file) {
            throw new ExportSaveFileException('Save file error - File has not created');
        }

        return new ExportResult($file, $strategy->getFileName(), $strategy->getMessage());
    }
}
