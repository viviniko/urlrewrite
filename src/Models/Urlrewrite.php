<?php

namespace Viviniko\Urlrewrite\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Urlrewrite extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('urlrewrite.urlrewrites_table');
    }
}