<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use NeptunSoftware\Accounting\Traits\AccountingJournal;

/**
 * Class Account
 *
 * @property    int                     $id
 * @property 	string					$name
 *
 */
class Account extends Model
{
	use AccountingJournal;
}


