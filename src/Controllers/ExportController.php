<?php

namespace OZiTAG\Tager\Backend\Export\Controllers;

use Illuminate\Support\Facades\URL;
use OZiTAG\Tager\Backend\Crud\Actions\IndexAction;
use OZiTAG\Tager\Backend\Crud\Actions\StoreOrUpdateAction;
use OZiTAG\Tager\Backend\Crud\Controllers\AdminCrudController;
use OZiTAG\Tager\Backend\Export\Enums\ExportSessionStatus;
use OZiTAG\Tager\Backend\Export\Features\ExportDownloadFeature;
use OZiTAG\Tager\Backend\Export\Features\ExportStrategiesFeature;
use OZiTAG\Tager\Backend\Export\Models\ExportSession;
use OZiTAG\Tager\Backend\Export\Operations\CreateExportOperation;
use OZiTAG\Tager\Backend\Export\Repositories\ExportSessionRepository;
use OZiTAG\Tager\Backend\Export\Requests\ExportStoreRequest;

class ExportController extends AdminCrudController
{
    public bool $hasCountAction = false;

    public bool $hasDeleteAction = false;

    public bool $hasUpdateAction = false;

    public function __construct(ExportSessionRepository $repository)
    {
        parent::__construct($repository);

        $this->setIndexAction((new IndexAction())->disableSearchByQuery());

        $this->setStoreAction(new StoreOrUpdateAction(ExportStoreRequest::class, CreateExportOperation::class));

        $this->setResourceFields([
            'id', 'strategy',
            'status:enum:' . ExportSessionStatus::class,
            'message',
            'history' => function (ExportSession $exportSession) {
                $result = [
                    [
                        'status' => ExportSessionStatus::label(ExportSessionStatus::Created),
                        'datetime' => $exportSession->created_at,
                    ]
                ];

                if ($exportSession->started_at) {
                    $result[] = [
                        'status' => ExportSessionStatus::label(ExportSessionStatus::InProgress),
                        'datetime' => $exportSession->started_at,
                    ];
                }

                if ($exportSession->completed_at) {
                    $result[] = [
                        'status' => ExportSessionStatus::label($exportSession->status),
                        'datetime' => $exportSession->completed_at,
                    ];
                }

                return $result;
            },
            'file' => function (ExportSession $exportSession) {
                return $exportSession->file ? $exportSession->file->getShortJson(null, URL::route(
                    'downloadExport', ['id' => $exportSession->id]
                )) : null;
            },
        ], true);
    }

    public function strategies()
    {
        return $this->serve(ExportStrategiesFeature::class);
    }

    public function download(int $id)
    {
        return $this->serve(ExportDownloadFeature::class, ['id' => $id]);
    }

}
