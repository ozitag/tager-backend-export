<?php

namespace OZiTAG\Tager\Backend\Export\Enums;

enum ExportSessionStatus: string
{
    case Created = 'CREATED';
    case InProgress = 'IN_PROGRESS';
    case Completed = 'COMPLETED';
    case Failure = 'FAILURE';

    public static function label(?string $value): string
    {
        switch ($value) {
            case self::Created->value:
                return 'Создан';
            case self::InProgress->value:
                return 'В процессе';
            case self::Completed->value:
                return 'Завершен';
            case self::Failure->value:
                return 'Ошибка';
            default:
                return 'Unknown';
        }
    }
}
