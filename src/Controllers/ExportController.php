<?php

namespace OZiTAG\Tager\Backend\Export\Controllers;

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
use OZiTAG\Tager\Backend\Export\TagerExport;
use OZiTAG\Tager\Backend\Fields\Base\Field;

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
                        'status' => ExportSessionStatus::label(ExportSessionStatus::Created->value),
                        'datetime' => $exportSession->created_at,
                    ]
                ];

                if ($exportSession->started_at) {
                    $result[] = [
                        'status' => ExportSessionStatus::label(ExportSessionStatus::InProgress->value),
                        'datetime' => $exportSession->started_at,
                    ];
                }

                if ($exportSession->completed_at) {
                    $result[] = [
                        'status' => ExportSessionStatus::label(ExportSessionStatus::from($exportSession->status)->value),
                        'datetime' => $exportSession->completed_at,
                    ];
                }

                return $result;
            },
            'file' => function (ExportSession $exportSession) {
                return $exportSession->file?->getShortJson();
            },
            'params' => function(ExportSession $exportSession){
                $params = $exportSession->params ? json_decode($exportSession->params, true) : [];
                if(empty($params)){
                    return [];
                }

                $strategy = TagerExport::getStrategy($exportSession->strategy);
                $fields = $strategy ? $strategy->conditionalFields() : [];

                $result = [];
                foreach($params as $param => $value){
                    if(empty($value)){
                        continue;
                    }

                    /** @var Field $field */
                   $field = $fields[$param] ?? null;

                   $label = $field ? $field->getLabel() : $param;

                   if(!$field){
                       $result[$param] = is_array($value) ? implode(', ', $value) : $value;
                   } else{
                       $typeInstance = $field->getTypeInstance();
                       $typeInstance->setValue($value);

                       $result[$field->getLabel()] = $typeInstance->getLabelValue();
                   }
                }

                return $result;
            }
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
