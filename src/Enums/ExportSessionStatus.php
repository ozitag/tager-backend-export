<?php

namespace OZiTAG\Tager\Backend\Export\Enums;

use OZiTAG\Tager\Backend\Core\Enums\Enum;

final class ExportSessionStatus extends Enum
{
    const Created = 'CREATED';
    const InProgress = 'IN_PROGRESS';
    const Completed = 'COMPLETED';
    const Failure = 'FAILURE';

    public static function label(?string $value): string
    {
        switch($value){
            case static::Created:
                return 'Создан';
            case static::InProgress:
                return 'В процессе';
            case static::Completed:
                return 'Завершен';
            case static::Failure:
                return 'Ошибка';
            default:
                return parent::label($value);
        }
    }
}
