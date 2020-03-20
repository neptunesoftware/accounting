<?php

namespace NeptuneSoftware\Accounting\Exceptions;

class InvalidLedgerType extends BaseException {

    public $message = 'Ledger type entry must be asset, liability, equity, income, expense';

}
