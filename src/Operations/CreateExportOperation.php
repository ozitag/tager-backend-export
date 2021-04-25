<?php

namespace OZiTAG\Tager\Backend\Export\Operations;

use Carbon\Carbon;
use OZiTAG\Tager\Backend\Core\Jobs\Operation;
use OZiTAG\Tager\Backend\Export\Enums\ExportSessionStatus;
use OZiTAG\Tager\Backend\Export\Jobs\RunExportSessionJob;
use OZiTAG\Tager\Backend\Export\Repositories\ExportSessionRepository;
use OZiTAG\Tager\Backend\Export\Requests\ExportStoreRequest;

class CreateExportOperation extends Operation
{
    protected ExportStoreRequest $request;

    public function __construct(ExportStoreRequest $request)
    {
        $this->request = $request;
    }

    public function handle(ExportSessionRepository $repository)
    {
        $model = $repository->fillAndSave([
            'strategy' => $this->request->strategy,
            'filename' => $this->request->filename,
            'format' => $this->request->format,
            'params' => json_encode([]),
            'status' => ExportSessionStatus::Created,
            'created_at' => Carbon::now(),
        ]);

        $this->run(RunExportSessionJob::class, [
            'id' => $model->id,
            'options' => [
                'delimeter' => $this->request->delimeter
            ]
        ]);

        return $model;
    }
}
