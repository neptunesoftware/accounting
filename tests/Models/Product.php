<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Product
 *
 * NOTE: This is only used for testing purposes.  It's not required for use in this package outside of testing.
 * @property    int                     $id
 * @property 	string					$name
 *
 */
class Product extends Model
{
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            /**
             * @var \Illuminate\Database\Eloquent\Model $model
             */
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }
}


