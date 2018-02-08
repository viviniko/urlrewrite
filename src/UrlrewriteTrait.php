<?php

namespace Viviniko\Urlrewrite;

use Viviniko\Urlrewrite\Models\Urlrewrite;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

trait UrlrewriteTrait
{
    /**
     * Boot the urlrewrite trait for a model.
     *
     * @return void
     */
    public static function bootUrlrewriteTrait()
    {
        static::saving(function ($model) {
            $key = $model->getUrlrewriteKeyName();
            $model->$key = ltrim($model->$key, '/');

            Validator::make(['request_path' => $model->$key], [
                'request_path' => 'max:255|unique:' . Config::get('urlrewrite.urlrewrites_table') . ',request_path' . ($model->urlrewrite ? (',' . $model->urlrewrite->id) : '')
            ])->validate();
        });

        static::saved(function ($model) {
            $key = $model->getUrlrewriteKeyName();
            if (!empty($model->$key)) {
                $model->urlrewrite()->updateOrCreate([
                    'entity_type' => $model->getMorphClass(),
                    'entity_id' => $model->id,
                ], [
                    'request_path' => $model->$key,
                    'target_path' => '',
                ]);
            }
        });

        static::deleted(function ($model) {
            $model->urlrewrite()->delete();
        });
    }

    public function urlrewrite()
    {
        return $this->morphOne(Urlrewrite::class, 'entity');
    }

    public function getUrlrewriteKeyName()
    {
        return 'url_rewrite';
    }
}