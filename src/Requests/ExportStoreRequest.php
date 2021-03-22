<?php

namespace OZiTAG\Tager\Backend\Export\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;
use OZiTAG\Tager\Backend\Crud\Requests\CrudFormRequest;

/**
 * Class ExportStoreRequest
 * @package OZiTAG\Tager\Backend\Export\Requests
 *
 * @property string $strategy
 */
class ExportStoreRequest extends CrudFormRequest
{
    public function rules()
    {
        return [
            'strategy' => 'required|string'
        ];
    }
}
