<?php

namespace OZiTAG\Tager\Backend\Export\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ozerich\FileStorage\Models\File;
use OZiTAG\Tager\Backend\Core\Models\TModel;

/**
 * Class ExportSession
 * @package OZiTAG\Tager\Backend\Export\Models
 *
 * @property integer $id
 * @property string $status
 * @property string $strategy
 * @property string $format
 * @property string $filename
 * @property string $params
 * @property string $error
 * @property string $file_id
 * @property string $file_name
 * @property string $created_at
 * @property string $started_at
 * @property string $completed_at
 *
 * @property File $file
 */
class ExportSession extends TModel
{
    public $timestamps = false;

    static string $defaultOrder = 'created_at DESC';

    protected $table = 'tager_export_sessions';

    protected $fillable = [
        'status', 'strategy', 'format', 'filename',
        'params', 'message', 'file_id', 'file_name',
        'created_at', 'started_at', 'completed_at'
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
