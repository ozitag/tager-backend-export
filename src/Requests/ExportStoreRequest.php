<?php

namespace OZiTAG\Tager\Backend\Export\Requests;

use Illuminate\Validation\Rules\Enum;
use OZiTAG\Tager\Backend\Crud\Requests\CrudFormRequest;
use OZiTAG\Tager\Backend\Export\Enums\ExportFileFormat;

/**
 * Class ExportStoreRequest
 * @package OZiTAG\Tager\Backend\Export\Requests
 *
 * @property string $strategy
 * @property string $params
 * @property string $filename
 * @property string $format
 * @property string $delimiter
 */
class ExportStoreRequest extends CrudFormRequest
{
    public function rules()
    {
        return [
            'strategy' => 'required|string',
            'filename' => 'required|string',
            'format' => ['required', new Enum(ExportFileFormat::class)],
            'delimiter' => 'string',
            'params' => 'nullable|array',
            'params.*.name' => 'string',
            'params.*.value' => 'present'
        ];
    }
}
