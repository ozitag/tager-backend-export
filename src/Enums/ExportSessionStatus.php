<?php

namespace OZiTAG\Tager\Backend\Export\Enums;

enum ExportSessionStatus: string
{
    case Created = 'CREATED';
    case InProgress = 'IN_PROGRESS';
    case Completed = 'COMPLETED';
    case Failure = 'FAILURE';
}
