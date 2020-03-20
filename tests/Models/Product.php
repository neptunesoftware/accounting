<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use NeptuneSoftware\Accounting\Traits\HasUUID;

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
    use HasUUID;
}


