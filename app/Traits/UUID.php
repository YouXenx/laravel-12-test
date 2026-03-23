<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UUID
{
    /**
     * Boot the trait.
     */
    protected static function bootUUID()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Tell Eloquent the primary key is not auto-incrementing.
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Tell Eloquent the primary key type is string.
     */
    public function getKeyType()
    {
        return 'string';
    }
}
