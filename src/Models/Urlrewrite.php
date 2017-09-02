<?php

namespace Viviniko\Urlrewrite\Models;

use Viviniko\Support\Database\Eloquent\Model;

class Urlrewrite extends Model
{
    protected $tableConfigKey = 'urlrewrite.urlrewrites_table';

    public $timestamps = false;

    protected $fillable = [
        'request_path', 'target_path', 'entity_type', 'entity_id',
    ];
}