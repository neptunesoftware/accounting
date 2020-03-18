<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use NeptuneSoftware\Accounting\Traits\AccountingJournal;

/**
 * Class User
 *
 * NOTE: This is only used for testing purposes.  It's not required for us
 *
 * @property    int                     $id
 * @property 	AccountingJournal		$journal
 *
 */
class User extends Model
{
	use AccountingJournal;

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


