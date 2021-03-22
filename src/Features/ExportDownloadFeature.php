<?php

namespace OZiTAG\Tager\Backend\Export\Features;

use Ozerich\FileStorage\Storage;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Core\Resources\SuccessResource;
use OZiTAG\Tager\Backend\Export\Models\ExportSession;
use OZiTAG\Tager\Backend\Export\Repositories\ExportSessionRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExportDownloadFeature extends Feature
{
    protected int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function handle(ExportSessionRepository $exportSessionRepository, Storage $fileStorage)
    {
        /** @var ExportSession $model */
        $model = $exportSessionRepository->find($this->id);
        if (!$model || !$model->file_id) {
            throw new NotFoundHttpException('File not found');
        }

        $fileStorage->sendDownloadFileResponse($model->file, $model->file_name . '.' . $model->file->ext);
    }
}
