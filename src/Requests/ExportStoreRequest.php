<?php

namespace OZiTAG\Tager\Backend\Export\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;
use OZiTAG\Tager\Backend\Crud\Requests\CrudFormRequest;
use OZiTAG\Tager\Backend\Export\Enums\ExportFileFormat;
use OZiTAG\Tager\Backend\Validation\Rule;

/**
 * Class ExportStoreRequest
 * @package OZiTAG\Tager\Backend\Export\Requests
 *
 * @property string $strategy
 * @property string $filename
 * @property string $format
 */
class ExportStoreRequest extends CrudFormRequest
{
    public function rules()
    {
        return [
            'strategy' => 'required|string',
            'filename' => 'required|string',
            'format' => ['required', Rule::in(ExportFileFormat::getValues())]
        ];
    }
}
