<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use NeptuneSoftware\Accounting\Traits\AccountingJournal;
use NeptuneSoftware\Accounting\Traits\HasUUID;

/**
 * Class Account
 *
 * @property    int                     $id
 * @property 	string					$name
 *
 */
class Account extends Model
{
	use AccountingJournal, HasUUID;

}


