<?php

namespace OZiTAG\Tager\Backend\Export\Enums;

enum ExportSessionStatus: string
{
    case Created = 'CREATED';
    case InProgress = 'IN_PROGRESS';
    case Completed = 'COMPLETED';
    case Failure = 'FAILURE';

    public static function label(?self $value): string
    {
        switch ($value) {
            case self::Created:
                return 'Создан';
            case self::InProgress:
                return 'В процессе';
            case self::Completed:
                return 'Завершен';
            case self::Failure:
                return 'Ошибка';
            default:
                return 'Unknown';
        }
    }
}
