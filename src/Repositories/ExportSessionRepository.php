<?php

namespace OZiTAG\Tager\Backend\Export\Repositories;

use Illuminate\Support\Facades\DB;
use OZiTAG\Tager\Backend\Core\Repositories\EloquentRepository;
use OZiTAG\Tager\Backend\Export\Models\ExportSession;

class ExportSessionRepository extends EloquentRepository
{
    public function __construct(ExportSession $model)
    {
        parent::__construct($model);
    }

    public function findByUUID(string $uuid): ?ExportSession
    {
        return $this->builder()->where('uuid', '=', $uuid)->first();
    }
}
