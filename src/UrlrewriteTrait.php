<?php

namespace Viviniko\Urlrewrite;

use Illuminate\Database\QueryException;
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
            $model->$key = trim($model->$key, " \t\n\r \v/");
            if (!empty($model->$key)) {
                $model->$key = '/' . $model->$key;
                Validator::make(['request_path' => $model->$key], [
                    'request_path' => 'max:255|unique:' . Config::get('urlrewrite.urlrewrites_table') . ',request_path' . ($model->urlrewrite ? (',' . $model->urlrewrite->id) : '')
                ])->validate();
            }
        });

        static::saved(function ($model) {
            $key = $model->getUrlrewriteKeyName();
            $model->$key = trim($model->$key, " \t\n\r \v/");
            if (!empty($model->$key)) {
                $model->$key = '/' . $model->$key;
                $model->urlrewrite()->updateOrCreate([
                    'entity_type' => $model->getMorphClass(),
                    'entity_id' => $model->id,
                ], [
                    'request_path' => $model->$key,
                ]);
            }
        });

        static::deleted(function ($model) {
            try {
                $model->urlrewrite()->delete();
            } catch (QueryException $e) {
                // ignored
            }
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