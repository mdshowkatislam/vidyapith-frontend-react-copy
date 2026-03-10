<?php

namespace App\Traits;

trait CreatedUpdatedBy
{
    public static function bootCreatedUpdatedBy()
    {
        // updating created_by and updated_by when model is created
        static::creating(function ($model) {
            if (!$model->isDirty('created_by')) {
                if(@app('sso-auth')->user()) {
                    $model->created_by = app('sso-auth')->user()->caid;
                }
            }
            if (!$model->isDirty('updated_by') && @app('sso-auth')->user()) {
                $model->updated_by = app('sso-auth')->user()->caid;
            }
        });

        // updating updated_by when model is updated
        static::updating(function ($model) {
            if (!$model->isDirty('updated_by') && @app('sso-auth')->user()) {
                $model->updated_by = app('sso-auth')->user()->caid;
            }
        });
    }
}