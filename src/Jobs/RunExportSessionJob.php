<?php

namespace OZiTAG\Tager\Backend\Export\Jobs;

use Carbon\Carbon;
use OZiTAG\Tager\Backend\Core\Jobs\Job;
use OZiTAG\Tager\Backend\Core\Jobs\QueueJob;
use OZiTAG\Tager\Backend\Export\Enums\ExportSessionStatus;
use OZiTAG\Tager\Backend\Export\Models\ExportSession;
use OZiTAG\Tager\Backend\Export\Repositories\ExportSessionRepository;
use OZiTAG\Tager\Backend\Export\Utils\Export;

class RunExportSessionJob extends QueueJob
{
    protected int $id;

    protected array $options;

    protected array $params;

    public function __construct(int $id, array $options = [], array $params = [])
    {
        $this->id = $id;

        $this->options = $options;

        $this->params = $params;
    }

    public function handle(ExportSessionRepository $exportSessionRepository, Export $export)
    {
        /** @var ExportSession $model */
        $model = $exportSessionRepository->find($this->id);
        if (!$model) {
            return;
        }

        dispatch(new SetExportSessionStatusJob($model, ExportSessionStatus::InProgress));

        try {
            $params = $model->params ? json_decode($model->params, true) : null;

            try {
                $exportResult = $export->run(
                    $model->strategy,
                    $model->filename,
                    $model->format,
                    $params,
                    $this->options
                );

                dispatch(new SetExportSessionStatusJob($model, ExportSessionStatus::Completed));

                $exportSessionRepository->set($model);
                $exportSessionRepository->fillAndSave([
                    'message' => $exportResult->getMessage(),
                    'file_id' => $exportResult->getFileId(),
                    'file_name' => $exportResult->getFileName(),
                ]);
            } catch (ExportException $exception) {
                dispatch(new SetExportSessionStatusJob($model, ExportSessionStatus::Failure, $exception->getMessage()));
            }

        } catch (\Exception $exception) {
            dispatch(new SetExportSessionStatusJob($model, ExportSessionStatus::Failure, (string)$exception));
        }
    }
}
