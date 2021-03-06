<?php

namespace OZiTAG\Tager\Backend\Export\Features;

use Illuminate\Http\Resources\Json\JsonResource;
use Ozerich\FileStorage\Storage;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Core\Resources\SuccessResource;
use OZiTAG\Tager\Backend\Export\Models\ExportSession;
use OZiTAG\Tager\Backend\Export\Repositories\ExportSessionRepository;
use OZiTAG\Tager\Backend\Export\TagerExport;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExportStrategiesFeature extends Feature
{
    public function handle()
    {
        $data = [];

        foreach (TagerExport::getStrategies() as $strategy) {
            $fields = [];
            foreach ($strategy->conditionalFields() as $name => $conditionalField) {
                $fields[] = $conditionalField->setName($name)->getJson();
            }

            $data[] = [
                'id' => $strategy->getId(),
                'name' => $strategy->getName(),
                'fields' => $fields
            ];
        }

        return new JsonResource($data);
    }
}
