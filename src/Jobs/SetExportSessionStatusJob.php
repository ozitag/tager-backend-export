<?php

namespace OZiTAG\Tager\Backend\Export\Jobs;

use Carbon\Carbon;
use OZiTAG\Tager\Backend\Core\Jobs\Job;
use OZiTAG\Tager\Backend\Core\Jobs\QueueJob;
use OZiTAG\Tager\Backend\Export\Enums\ExportSessionStatus;
use OZiTAG\Tager\Backend\Export\Models\ExportSession;
use OZiTAG\Tager\Backend\Export\Repositories\ExportSessionRepository;

class SetExportSessionStatusJob extends Job
{
    protected ExportSession $exportSession;

    protected string $status;

    protected ?string $message;

    public function __construct(ExportSession $exportSession, string $status, ?string $message = null)
    {
        $this->exportSession = $exportSession;

        $this->status = $status;

        $this->message = $message;
    }

    public function handle(ExportSessionRepository $exportSessionRepository)
    {
        $exportSessionRepository->set($this->exportSession);

        if ($this->status == ExportSessionStatus::InProgress) {
            $exportSessionRepository->fillAndSave([
                'status' => ExportSessionStatus::InProgress,
                'started_at' => Carbon::now()
            ]);
        } else if ($this->status == ExportSessionStatus::Completed) {
            $exportSessionRepository->fillAndSave([
                'status' => ExportSessionStatus::Completed,
                'completed_at' => Carbon::now(),
                'message' => $this->message
            ]);
        } else if ($this->status == ExportSessionStatus::Failure) {
            $exportSessionRepository->fillAndSave([
                'status' => ExportSessionStatus::Failure,
                'completed_at' => Carbon::now(),
                'message' => $this->message
            ]);
        }
    }
}
